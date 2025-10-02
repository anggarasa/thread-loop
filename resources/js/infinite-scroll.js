/**
 * Infinite Scroll Functionality
 * Handles automatic loading of more posts when user scrolls near bottom
 */

let isLoading = false;
let scrollTimeout = null;

function isNearBottom() {
    return (
        window.innerHeight + window.scrollY >= document.body.offsetHeight - 1000
    );
}

function loadMorePosts() {
    if (isLoading) {
        return;
    }

    // Check if we're on home page
    const homePageElement = document.querySelector('[data-page="home"]');
    if (!homePageElement) {
        return;
    }

    // Check if Livewire component exists and has more posts
    if (typeof Livewire !== "undefined") {
        // Find the Livewire component by looking for wire:id attribute
        const wireElement = document.querySelector("[wire\\:id]");
        if (wireElement) {
            const wireId = wireElement.getAttribute("wire:id");
            const component = window.Livewire.find(wireId);

            if (component && component.hasMorePosts && !component.loading) {
                isLoading = true;
                component
                    .call("loadMore")
                    .then(() => {
                        isLoading = false;
                    })
                    .catch(() => {
                        isLoading = false;
                    });
            }
        } else {
            // Fallback: try to find component by looking for Livewire component in the page
            const allComponents = window.Livewire.all();
            for (let component of allComponents) {
                if (component.hasMorePosts && !component.loading) {
                    isLoading = true;
                    component
                        .call("loadMore")
                        .then(() => {
                            isLoading = false;
                        })
                        .catch(() => {
                            isLoading = false;
                        });
                    break;
                }
            }
        }
    }
}

function setupInfiniteScroll() {
    // Only setup if we're on the home page
    const homePageElement = document.querySelector('[data-page="home"]');
    if (!homePageElement) {
        return;
    }

    const postsContainer = document.getElementById("posts-container");
    if (!postsContainer) {
        console.warn("Posts container not found");
        return;
    }

    // Throttled scroll handler
    const throttledScrollHandler = () => {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }

        scrollTimeout = setTimeout(() => {
            if (isNearBottom()) {
                loadMorePosts();
            }
        }, 100);
    };

    // Remove existing scroll listener if any
    window.removeEventListener("scroll", throttledScrollHandler);

    // Add new scroll listener
    window.addEventListener("scroll", throttledScrollHandler, {
        passive: true,
    });
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", setupInfiniteScroll);

// Re-initialize for Livewire navigation
document.addEventListener("livewire:navigated", () => {
    // Reset loading state
    isLoading = false;
    if (scrollTimeout) {
        clearTimeout(scrollTimeout);
    }

    // Setup infinite scroll for the new page
    setTimeout(setupInfiniteScroll, 100);
});

// Reset loading state on Livewire updates
document.addEventListener("livewire:updated", () => {
    isLoading = false;
});
