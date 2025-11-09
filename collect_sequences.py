from pathlib import Path
path=Path('app/Http/Controllers/Clean/Formateur/Formation/-FormationLessonController.php')
text=path.read_text(encoding='utf-8')
marker=chr(0xc3)
seqs=set()
for i in range(len(text)-1):
    if text[i]==marker:
        seqs.add(text[i:i+2])
print(seqs)
