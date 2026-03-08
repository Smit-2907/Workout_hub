/**
 * Workout Hub 2.0 - Production API Helper
 * Handles global loading states, error reporting, and fetch wrappers.
 */

async function apiRequest(action, method = 'GET', data = null) {
    const loader = createLoader();
    document.body.appendChild(loader);

    try {
        const endpoint = window.API_ENDPOINT || '../backend/api.php';
        let url = endpoint;
        let options = {
            method: method,
            headers: {}
        };

        // Add Bearer Token if exists
        const token = localStorage.getItem('auth_token');
        if (token) {
            options.headers['Authorization'] = `Bearer ${token}`;
        }

        if (method === 'GET') {
            url += `?action=${action}`;
            if (data) {
                Object.keys(data).forEach(key => url += `&${key}=${data[key]}`);
            }
        } else {
            const formData = new FormData();
            formData.append('action', action);
            if (data) {
                Object.keys(data).forEach(key => {
                    if (data[key] !== null) formData.append(key, data[key]);
                });
            }
            options.body = formData;
        }

        const response = await fetch(url, options);
        if (!response.ok) throw new Error('Network response was not ok');

        const result = await response.json();

        if (result.status === 'error') {
            showToast(result.message, 'error');
            return null;
        }

        return result;
    } catch (error) {
        console.error('API Error:', error);
        showToast('A connection error occurred. Please check your internet.', 'error');
        return null;
    } finally {
        loader.remove();
    }
}

/**
 * Global UI Helpers
 */
function createLoader() {
    const div = document.createElement('div');
    div.id = 'global-loader';
    div.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(5px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999;
    `;
    div.innerHTML = '<div class="loader-spinner"></div>';
    return div;
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type} animate`;
    toast.style.cssText = `
        position: fixed; bottom: 2rem; right: 2rem;
        padding: 1rem 2rem; border-radius: 12px;
        background: ${type === 'success' ? '#10b981' : '#f43f5e'};
        color: white; font-weight: 600; box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        z-index: 10000;
    `;
    toast.innerText = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

// Add Spinner Style to head
const style = document.createElement('style');
style.innerHTML = `
    .loader-spinner {
        width: 50px; height: 50px;
        border: 5px solid rgba(255,255,255,0.1);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
`;
document.head.appendChild(style);
