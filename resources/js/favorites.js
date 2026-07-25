// Frictionless favourites for renters — stored in the browser, no account needed.
const KEY = 'nyumbani_favorites';

function read() {
    try {
        const raw = localStorage.getItem(KEY);
        const ids = raw ? JSON.parse(raw) : [];
        return Array.isArray(ids) ? ids.map(String) : [];
    } catch {
        return [];
    }
}

function write(ids) {
    localStorage.setItem(KEY, JSON.stringify([...new Set(ids.map(String))]));
}

export function getFavorites() {
    return read();
}

export function isFavorite(id) {
    return read().includes(String(id));
}

export function toggleFavorite(id) {
    id = String(id);
    const ids = read();
    const idx = ids.indexOf(id);
    if (idx === -1) {
        ids.push(id);
    } else {
        ids.splice(idx, 1);
    }
    write(ids);
    updateCount();
    return ids.includes(id);
}

function updateCount() {
    const count = read().length;
    document.querySelectorAll('[data-fav-count]').forEach((el) => {
        el.textContent = count;
        el.classList.toggle('hidden', count === 0);
    });
}

function reflectButton(btn) {
    const on = isFavorite(btn.dataset.favId);
    btn.classList.toggle('is-fav', on);
    btn.setAttribute('aria-pressed', on ? 'true' : 'false');
}

function wireButtons(root = document) {
    root.querySelectorAll('[data-fav-btn]').forEach((btn) => {
        if (btn.dataset.favWired) return;
        btn.dataset.favWired = '1';
        reflectButton(btn);
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            toggleFavorite(btn.dataset.favId);
            // Reflect on every button pointing at the same property (cards + detail).
            document
                .querySelectorAll(`[data-fav-btn][data-fav-id="${btn.dataset.favId}"]`)
                .forEach(reflectButton);
            document.dispatchEvent(new CustomEvent('favorites:changed'));
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    wireButtons();
    updateCount();
});

// Expose so dynamically-injected cards (Saved page) can be wired up.
window.NyumbaniFavorites = { getFavorites, isFavorite, toggleFavorite, wireButtons };
