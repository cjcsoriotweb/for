<?php

namespace App\Http\Controllers\Clean\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class RechargeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function show(Team $team)
    {
        if (! Auth::user()->belongsToTeam($team) && ! Auth::user()->superadmin) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Accès non autorisé.');
        }

        return view('clean.organisateur.recharge', compact('team'));
    }

    public function createCheckoutSession(Request $request, Team $team)
    {
        if (! Auth::user()->belongsToTeam($team) && ! Auth::user()->superadmin) {
            return response()->json(['error' => 'Accès non autorisé.'], 403);
        }

        $request->validate([
            'amount' => 'required|integer|min:100|max:100000', // Montant en centimes (1€ à 1000€)
        ]);

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Recharge solde - ' . $team->name,
                            'description' => 'Recharge du solde de l\'équipe ' . $team->name,
                        ],
                        'unit_amount' => $request->amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('organisateur.recharge.success', $team) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('organisateur.recharge.show', $team),
                'metadata' => [
                    'team_id' => $team->id,
                    'amount' => $request->amount,
                ],
            ]);

            return response()->json(['url' => $session->url]);
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return response()->json(['error' => 'Erreur d\'authentification Stripe. Vérifiez vos clés API.'], 500);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['error' => 'Requête Stripe invalide: ' . $e->getError()->message], 400);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            return response()->json(['error' => 'Erreur de connexion à Stripe. Vérifiez votre connexion internet.'], 500);
        } catch (\Stripe\Exception\RateLimitException $e) {
            return response()->json(['error' => 'Trop de requêtes vers Stripe. Veuillez réessayer dans quelques minutes.'], 429);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création de la session de paiement: ' . $e->getMessage()], 500);
        }
    }

    public function success(Request $request, Team $team)
    {
        if (! Auth::user()->belongsToTeam($team) && !Auth::user()->superadmin) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Accès non autorisé.');
        }

        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect()->route('organisateur.recharge.show', $team)
                ->with('error', 'Session de paiement invalide.');
        }

        try {
            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                // Créditer le solde de l'équipe
                $amount = $session->metadata->amount / 100; // Convertir en euros
                $team->increment('money', $amount);

                return view('clean.organisateur.recharge-success', [
                    'team' => $team,
                    'amount' => $amount,
                    'session' => $session,
                ]);
            } else {
                return redirect()->route('organisateur.recharge.show', $team)
                    ->with('error', 'Le paiement n\'a pas été complété.');
            }
        } catch (\Exception $e) {
            return redirect()->route('organisateur.recharge.show', $team)
                ->with('error', 'Erreur lors de la vérification du paiement.');
        }
    }
}
