export const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || '/backend/api';

export const endpoints = {
    products: `${API_BASE_URL}/products.php`,
    categories: `${API_BASE_URL}/categories.php`,
    orders: `${API_BASE_URL}/orders.php`,
    storage: `${API_BASE_URL}/storage.php`,
    settings: `${API_BASE_URL}/settings.php`,
};

export async function fetchProducts() {
    const response = await fetch(endpoints.products);
    if (!response.ok) throw new Error('Failed to fetch products');
    const json = await response.json();
    return json.data;
}

export async function fetchCategories() {
    const response = await fetch(endpoints.categories);
    if (!response.ok) throw new Error('Failed to fetch categories');
    const json = await response.json();
    return json.data;
}

export async function fetchStorage() {
    const response = await fetch(endpoints.storage);
    if (!response.ok) throw new Error('Failed to fetch storage');
    const json = await response.json();
    return json.data;
}

export async function fetchSettings() {
    const response = await fetch(endpoints.settings);
    if (!response.ok) throw new Error('Failed to fetch settings');
    const json = await response.json();
    return json.data;
}

export async function createOrder(orderData: any) {
    const response = await fetch(endpoints.orders, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(orderData),
    });
    if (!response.ok) throw new Error('Failed to create order');
    return await response.json();
}
