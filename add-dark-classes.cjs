const fs = require('fs');
const path = require('path');

const mappings = {
    'bg-white': 'bg-slate-800',
    'bg-slate-50': 'bg-slate-900',
    'bg-slate-100': 'bg-slate-800/50',
    'bg-slate-200': 'bg-slate-700',
    'text-slate-900': 'text-white',
    'text-slate-800': 'text-slate-200',
    'text-slate-700': 'text-slate-300',
    'text-slate-600': 'text-slate-400',
    'text-slate-500': 'text-slate-400',
    'text-slate-400': 'text-slate-500',
    'border-slate-100': 'border-slate-700',
    'border-slate-200': 'border-slate-600',
    'border-slate-300': 'border-slate-500',
    'ring-slate-200': 'ring-slate-700',
    'ring-white': 'ring-slate-800',
    'divide-slate-100': 'divide-slate-700',
    'divide-slate-200': 'divide-slate-600',
    'shadow-sm': 'shadow-none',
    'shadow': 'shadow-none',
    'shadow-lg': 'shadow-none',
    'text-indigo-600': 'text-indigo-400',
    'text-indigo-500': 'text-indigo-400',
    'text-indigo-700': 'text-indigo-300',
    'bg-indigo-600': 'bg-indigo-500',
    'bg-indigo-50': 'bg-indigo-900/50',
    'text-emerald-700': 'text-emerald-400',
    'text-emerald-600': 'text-emerald-400',
    'bg-emerald-100': 'bg-emerald-900/50',
    'bg-emerald-50': 'bg-emerald-900/30',
    'text-red-700': 'text-red-400',
    'text-red-600': 'text-red-400',
    'bg-red-100': 'bg-red-900/50',
    'bg-red-50': 'bg-red-900/30',
    'text-amber-700': 'text-amber-400',
    'text-amber-600': 'text-amber-400',
    'bg-amber-100': 'bg-amber-900/50',
    'bg-amber-50': 'bg-amber-900/30',
};

function processDirectory(dir) {
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const fullPath = path.join(dir, file);
        if (fs.statSync(fullPath).isDirectory()) {
            processDirectory(fullPath);
        } else if (fullPath.endsWith('.blade.php')) {
            let content = fs.readFileSync(fullPath, 'utf8');
            const originalContent = content;

            for (const [light, dark] of Object.entries(mappings)) {
                // (?<![\w-]) means no word char or hyphen before
                // ((?:[a-z0-9]+:)*) matches prefixes like hover: focus: sm:
                // (?![\w-]) means no word char or hyphen after
                const regex = new RegExp(`(?<![\\w-])((?:[a-z0-9]+:)*)(${light})(?![\\w-])`, 'g');
                
                content = content.replace(regex, (match, prefixes, className) => {
                    if (prefixes.includes('dark:')) return match; // already handled
                    
                    // avoid replacing if it already has the dark class following it
                    // e.g., if we run the script twice.
                    return `${prefixes}${className} dark:${prefixes}${dark}`;
                });
            }
            
            // basic deduplication if run multiple times: "class dark:class dark:class" -> "class dark:class"
            for (const [light, dark] of Object.entries(mappings)) {
                 const regexDouble = new RegExp(`(?<![\\w-])((?:[a-z0-9]+:)*)(${light})\\s+dark:\\1${dark}\\s+dark:\\1${dark}(?![\\w-])`, 'g');
                 content = content.replace(regexDouble, `$1$2 dark:$1${dark}`);
            }

            if (content !== originalContent) {
                fs.writeFileSync(fullPath, content, 'utf8');
                console.log('Updated', fullPath);
            }
        }
    }
}
processDirectory(path.join(__dirname, 'resources', 'views'));
