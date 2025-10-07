/**
 * Simple and Robust Infinite Scroll & Video Autoplay
 * Production-ready implementation without debug logs
 */

class SimpleFeedManager {
    constructor() {
        this.isLoading = false;
        this.videoElements = new Map();
        this.scrollTimeout = null;

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
        this.setupVideoElements();
        this.setupInfiniteScroll();
        this.setupLivewireListeners();
    }

    setupVideoElements() {
        const videos = document.querySelectorAll(".video-autoplay");

        videos.forEach((video, index) => {
            this.setupVideo(video, index);
        });
    }

    setupVideo(video, index) {
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
            this.videoElements.get(video).isPlaying = true;
            this.pauseOtherVideos(video);
        });

        video.addEventListener("pause", () => {
            this.videoElements.get(video).isPlaying = false;
        });

        video.addEventListener("ended", () => {
            video.currentTime = 0;
            video.play().catch(() => {});
        });

        video.addEventListener("error", (e) => {
            this.videoElements.get(video).isPlaying = false;
        });

        // Initial visibility check
        this.checkVideoVisibility(video);
    }

    setupInfiniteScroll() {
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
            this.loadMoreContent();
        }
    }

    async playVideo(video) {
        const videoData = this.videoElements.get(video);
        if (!videoData || videoData.isPlaying) return;

        try {
            await video.play();
            videoData.isPlaying = true;
            videoData.hasPlayed = true;
        } catch (error) {
            // Try with muted if autoplay fails
            try {
                video.muted = true;
                await video.play();
                videoData.isPlaying = true;
                videoData.hasPlayed = true;
            } catch (mutedError) {
                // Autoplay completely blocked
            }
        }
    }

    pauseVideo(video) {
        const videoData = this.videoElements.get(video);
        if (!videoData || !videoData.isPlaying) return;

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
            return;
        }

        // Check if we're on a supported page
        const homePageElement = document.querySelector('[data-page="home"]');
        const searchPageElement = document.querySelector(
            '[data-page="search"]'
        );

        if (!homePageElement && !searchPageElement) {
            return;
        }

        // Find Livewire component
        const wireElement = document.querySelector("[wire\\:id]");
        if (!wireElement) {
            return;
        }

        const wireId = wireElement.getAttribute("wire:id");
        const component = window.Livewire.find(wireId);

        if (!component) {
            return;
        }

        if (component.loading) {
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
            return;
        }

        this.isLoading = true;

        component
            .call("loadMore")
            .then(() => {
                this.isLoading = false;
                // Refresh video elements after content loads
                setTimeout(() => this.refreshVideoElements(), 100);
            })
            .catch((error) => {
                this.isLoading = false;
            });
    }

    refreshVideoElements() {
        // Find new video elements
        const allVideos = document.querySelectorAll(".video-autoplay");
        const newVideos = Array.from(allVideos).filter(
            (video) => !this.videoElements.has(video)
        );

        newVideos.forEach((video, index) => {
            this.setupVideo(video, this.videoElements.size + index);
        });
    }

    setupLivewireListeners() {
        // Re-initialize on Livewire navigation
        document.addEventListener("livewire:navigated", () => {
            this.destroy();
            setTimeout(() => this.init(), 100);
        });

        // Refresh on Livewire updates
        document.addEventListener("livewire:updated", () => {
            this.refreshVideoElements();
        });
    }

    destroy() {
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
    window.simpleFeedManager = new SimpleFeedManager();
});

// Re-initialize for Livewire navigation
document.addEventListener("livewire:navigated", () => {
    if (window.simpleFeedManager) {
        window.simpleFeedManager.destroy();
    }
    setTimeout(() => {
        window.simpleFeedManager = new SimpleFeedManager();
    }, 100);
});

// Export for global access
window.SimpleFeedManager = SimpleFeedManager;
