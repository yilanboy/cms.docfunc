export function back() {
    window.history.back();
}

export function stopPropagation(fn: (event: Event) => void) {
    return function (this: (event: Event) => void, event: Event) {
        event.stopPropagation();
        fn.call(this, event);
    };
}
