/**
 * Simple and Robust Infinite Scroll & Video Autoplay
 * Fallback implementation for better compatibility
 */

class SimpleFeedManager {
    constructor() {
        this.isLoading = false;
        this.videoElements = new Map();
        this.scrollTimeout = null;

        console.log("SimpleFeedManager: Initializing...");
        this.init();
    }

    init() {
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        console.log("SimpleFeedManager: Setting up...");

        this.setupVideoElements();
        this.setupInfiniteScroll();
        this.setupLivewireListeners();

        console.log("SimpleFeedManager: Setup complete");
    }

    setupVideoElements() {
        const videos = document.querySelectorAll(".video-autoplay");
        console.log(`SimpleFeedManager: Found ${videos.length} videos`);

        videos.forEach((video, index) => {
            this.setupVideo(video, index);
        });
    }

    setupVideo(video, index) {
        console.log(`SimpleFeedManager: Setting up video ${index + 1}`);

        // Set video properties
        video.playsInline = true;
        video.preload = "metadata";

        // Store video data
        this.videoElements.set(video, {
            isPlaying: false,
            hasPlayed: false,
            isVisible: false,
        });

        // Click to play/pause
        video.addEventListener("click", (e) => {
            e.preventDefault();
            this.toggleVideo(video);
        });

        // Video events
        video.addEventListener("play", () => {
            console.log("SimpleFeedManager: Video playing");
            this.videoElements.get(video).isPlaying = true;
            this.pauseOtherVideos(video);
        });

        video.addEventListener("pause", () => {
            console.log("SimpleFeedManager: Video paused");
            this.videoElements.get(video).isPlaying = false;
        });

        video.addEventListener("ended", () => {
            console.log("SimpleFeedManager: Video ended, looping...");
            video.currentTime = 0;
            video.play().catch(() => {});
        });

        video.addEventListener("error", (e) => {
            console.warn("SimpleFeedManager: Video error:", e);
            this.videoElements.get(video).isPlaying = false;
        });

        // Initial visibility check
        this.checkVideoVisibility(video);
    }

    setupInfiniteScroll() {
        console.log("SimpleFeedManager: Setting up infinite scroll...");

        // Throttled scroll handler
        const scrollHandler = () => {
            if (this.scrollTimeout) {
                clearTimeout(this.scrollTimeout);
            }

            this.scrollTimeout = setTimeout(() => {
                this.handleScroll();
            }, 100);
        };

        window.addEventListener("scroll", scrollHandler, { passive: true });
        window.addEventListener("resize", scrollHandler, { passive: true });

        // Initial check
        this.handleScroll();
    }

    handleScroll() {
        // Check video visibility
        this.videoElements.forEach((videoData, video) => {
            this.checkVideoVisibility(video);
        });

        // Check infinite scroll
        this.checkInfiniteScroll();
    }

    checkVideoVisibility(video) {
        const rect = video.getBoundingClientRect();
        const windowHeight = window.innerHeight;
        const isVisible = rect.top < windowHeight && rect.bottom > 0;

        const videoData = this.videoElements.get(video);
        if (!videoData) return;

        if (isVisible && !videoData.isVisible) {
            videoData.isVisible = true;
            this.playVideo(video);
        } else if (!isVisible && videoData.isVisible) {
            videoData.isVisible = false;
            this.pauseVideo(video);
        }
    }

    checkInfiniteScroll() {
        if (this.isLoading) return;

        const scrollTop =
            window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;

        // Check if we're near bottom (within 200px)
        if (scrollTop + windowHeight >= documentHeight - 200) {
            console.log("SimpleFeedManager: Near bottom, loading more...");
            this.loadMoreContent();
        }
    }

    async playVideo(video) {
        const videoData = this.videoElements.get(video);
        if (!videoData || videoData.isPlaying) return;

        try {
            console.log("SimpleFeedManager: Attempting to play video");
            await video.play();
            videoData.isPlaying = true;
            videoData.hasPlayed = true;
            console.log("SimpleFeedManager: Video playing successfully");
        } catch (error) {
            console.log("SimpleFeedManager: Autoplay prevented:", error);
            // Try with muted if autoplay fails
            try {
                video.muted = true;
                await video.play();
                videoData.isPlaying = true;
                videoData.hasPlayed = true;
                console.log(
                    "SimpleFeedManager: Video playing with muted audio"
                );
            } catch (mutedError) {
                console.log(
                    "SimpleFeedManager: Even muted autoplay failed:",
                    mutedError
                );
            }
        }
    }

    pauseVideo(video) {
        const videoData = this.videoElements.get(video);
        if (!videoData || !videoData.isPlaying) return;

        console.log("SimpleFeedManager: Pausing video");
        video.pause();
        videoData.isPlaying = false;
    }

    pauseOtherVideos(currentVideo) {
        this.videoElements.forEach((videoData, video) => {
            if (video !== currentVideo && videoData.isPlaying) {
                this.pauseVideo(video);
            }
        });
    }

    toggleVideo(video) {
        const videoData = this.videoElements.get(video);
        if (!videoData) return;

        if (videoData.isPlaying) {
            this.pauseVideo(video);
        } else {
            this.playVideo(video);
        }
    }

    loadMoreContent() {
        if (this.isLoading) {
            console.log("SimpleFeedManager: Already loading, skipping...");
            return;
        }

        console.log("SimpleFeedManager: Loading more content...");

        // Check if we're on a supported page
        const homePageElement = document.querySelector('[data-page="home"]');
        const searchPageElement = document.querySelector(
            '[data-page="search"]'
        );

        if (!homePageElement && !searchPageElement) {
            console.log("SimpleFeedManager: Not on supported page");
            return;
        }

        // Find Livewire component
        const wireElement = document.querySelector("[wire\\:id]");
        if (!wireElement) {
            console.log("SimpleFeedManager: No Livewire component found");
            return;
        }

        const wireId = wireElement.getAttribute("wire:id");
        const component = window.Livewire.find(wireId);

        if (!component) {
            console.log("SimpleFeedManager: Livewire component not found");
            return;
        }

        if (component.loading) {
            console.log("SimpleFeedManager: Component already loading");
            return;
        }

        // Check if we have more content
        let hasMore = false;
        if (homePageElement) {
            hasMore = component.hasMorePosts;
        } else if (searchPageElement) {
            const activeTab = component.activeTab || "posts";
            hasMore =
                activeTab === "posts"
                    ? component.hasMorePosts
                    : component.hasMoreUsers;
        }

        if (!hasMore) {
            console.log("SimpleFeedManager: No more content to load");
            return;
        }

        this.isLoading = true;
        console.log("SimpleFeedManager: Calling loadMore...");

        component
            .call("loadMore")
            .then(() => {
                console.log("SimpleFeedManager: Load more successful");
                this.isLoading = false;
                // Refresh video elements after content loads
                setTimeout(() => this.refreshVideoElements(), 100);
            })
            .catch((error) => {
                console.error(
                    "SimpleFeedManager: Error loading more content:",
                    error
                );
                this.isLoading = false;
            });
    }

    refreshVideoElements() {
        console.log("SimpleFeedManager: Refreshing video elements...");

        // Find new video elements
        const allVideos = document.querySelectorAll(".video-autoplay");
        const newVideos = Array.from(allVideos).filter(
            (video) => !this.videoElements.has(video)
        );

        console.log(`SimpleFeedManager: Found ${newVideos.length} new videos`);

        newVideos.forEach((video, index) => {
            this.setupVideo(video, this.videoElements.size + index);
        });
    }

    setupLivewireListeners() {
        // Re-initialize on Livewire navigation
        document.addEventListener("livewire:navigated", () => {
            console.log(
                "SimpleFeedManager: Livewire navigated, reinitializing..."
            );
            this.destroy();
            setTimeout(() => this.init(), 100);
        });

        // Refresh on Livewire updates
        document.addEventListener("livewire:updated", () => {
            console.log("SimpleFeedManager: Livewire updated, refreshing...");
            this.refreshVideoElements();
        });
    }

    destroy() {
        console.log("SimpleFeedManager: Destroying...");

        // Clear video elements
        this.videoElements.clear();
        this.isLoading = false;

        // Clear timeouts
        if (this.scrollTimeout) {
            clearTimeout(this.scrollTimeout);
        }
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    console.log("SimpleFeedManager: DOM ready, initializing...");
    window.simpleFeedManager = new SimpleFeedManager();
});

// Re-initialize for Livewire navigation
document.addEventListener("livewire:navigated", () => {
    console.log("SimpleFeedManager: Livewire navigated, reinitializing...");
    if (window.simpleFeedManager) {
        window.simpleFeedManager.destroy();
    }
    setTimeout(() => {
        window.simpleFeedManager = new SimpleFeedManager();
    }, 100);
});

// Export for global access
window.SimpleFeedManager = SimpleFeedManager;

console.log("SimpleFeedManager: Script loaded");
