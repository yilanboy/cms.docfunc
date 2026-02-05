// Toast notification system state management
export type ToastType = "success" | "info" | "warning" | "danger";

export interface ToastProps {
    id: string;
    type: ToastType;
    message: string;
    description?: string;
}

export const TOAST_DURATION = 5000;
export const MAX_TOASTS = 3;

class Toasts {
    items = $state<ToastProps[]>([]);

    get topItems() {
        return this.items.slice(0, 3);
    }

    add(props: Omit<ToastProps, "id">) {
        const id = crypto.randomUUID();
        const toast = { id, ...props };

        this.items.unshift(toast);

        if (this.items.length > MAX_TOASTS) {
            this.destroy(this.items[this.items.length - 1].id);
        }

        setTimeout(() => this.destroy(id), TOAST_DURATION);
    }

    destroy(id: string) {
        this.items = this.items.filter((t) => t.id !== id);
    }
}

export const toasts = new Toasts();
