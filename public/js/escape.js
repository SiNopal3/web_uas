/**
 * Helper pencegahan Cross-Site Scripting (XSS).
 * Mengubah karakter khusus HTML menjadi entitas yang aman sebelum disisipkan ke DOM.
 */
function escapeHtml(unsafe) {
    if (unsafe === null || unsafe === undefined) return '';
    return String(unsafe)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
