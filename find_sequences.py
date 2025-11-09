from pathlib import Path
import re
marker=chr(0xc3)
text=Path('app/Http/Controllers/Clean/Formateur/Formation/-FormationLessonController.php').read_text(encoding='utf-8')
matches=set(re.findall(re.escape(marker) + '.', text))
print(matches)
