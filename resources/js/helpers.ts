export function back() {
    window.history.back();
}

export function once(fn: ((event: Event) => void) | null) {
    return function (this: (event: Event) => void, event: Event) {
        if (fn) fn.call(this, event);
        fn = null;
    };
}

export function preventDefault(fn: (event: Event) => void) {
    return function (this: (event: Event) => void, event: Event) {
        event.preventDefault();
        fn.call(this, event);
    };
}

export function stopPropagation(fn: (event: Event) => void) {
    return function (this: (event: Event) => void, event: Event) {
        event.stopPropagation();
        fn.call(this, event);
    };
}
