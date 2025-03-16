export function formatDate(date: Date): string {
    return date.toISOString().split('T')[0];
}

export function generateUniqueId(): string {
    return 'id-' + Math.random().toString(36).substr(2, 9);
}

export function parseJson<T>(jsonString: string): T | null {
    try {
        return JSON.parse(jsonString);
    } catch (error) {
        console.error('Invalid JSON string:', error);
        return null;
    }
}