/**
 * Video Autoplay Functionality
 * Handles automatic video playback when videos come into viewport
 */

class VideoAutoplay {
    constructor() {
        this.videos = document.querySelectorAll(".video-autoplay");
        this.isScrolling = false;
        this.scrollTimeout = null;
        this.observers = new Map();

        this.init();
    }

    init() {
        if (this.videos.length === 0) return;

        this.setupEventListeners();
        this.setupVideoEvents();
        this.setupIntersectionObserver();
        this.handleVideoVisibility();
    }

    setupEventListeners() {
        // Throttled scroll handler for better performance
        const throttledScrollHandler = () => {
            if (!this.isScrolling) {
                this.isScrolling = true;
                requestAnimationFrame(() => {
                    this.handleVideoVisibility();
                    this.isScrolling = false;
                });
            }
        };

        // Debounced scroll handler
        const debouncedScrollHandler = () => {
            clearTimeout(this.scrollTimeout);
            this.scrollTimeout = setTimeout(throttledScrollHandler, 100);
        };

        window.addEventListener("scroll", debouncedScrollHandler, {
            passive: true,
        });
        window.addEventListener("resize", debouncedScrollHandler, {
            passive: true,
        });
    }

    setupVideoEvents() {
        this.videos.forEach((video) => {
            // Loading states
            video.addEventListener("loadstart", () => {
                video.classList.add("loading");
            });

            video.addEventListener("canplay", () => {
                video.classList.remove("loading");
                video.classList.add("playing");
            });

            video.addEventListener("play", () => {
                video.classList.add("playing");
                this.pauseOtherVideos(video);
            });

            video.addEventListener("pause", () => {
                video.classList.remove("playing");
            });

            video.addEventListener("error", (e) => {
                console.warn("Video error:", e);
                video.classList.remove("loading", "playing");
            });

            // Click to play/pause
            video.addEventListener("click", (e) => {
                e.preventDefault();
                if (video.paused) {
                    video
                        .play()
                        .catch((err) => console.log("Play prevented:", err));
                } else {
                    video.pause();
                }
            });
        });
    }

    setupIntersectionObserver() {
        if (!("IntersectionObserver" in window)) return;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    const video = entry.target;
                    const isVisible =
                        entry.isIntersecting && entry.intersectionRatio >= 0.5;

                    if (isVisible) {
                        this.playVideo(video);
                    } else {
                        this.pauseVideo(video);
                    }
                });
            },
            {
                threshold: 0.5,
                rootMargin: "0px",
            }
        );

        this.videos.forEach((video) => {
            observer.observe(video);
            this.observers.set(video, observer);
        });
    }

    isMostlyVisible(element) {
        const rect = element.getBoundingClientRect();
        const windowHeight =
            window.innerHeight || document.documentElement.clientHeight;
        const windowWidth =
            window.innerWidth || document.documentElement.clientWidth;

        const visibleHeight =
            Math.min(rect.bottom, windowHeight) - Math.max(rect.top, 0);
        const visibleWidth =
            Math.min(rect.right, windowWidth) - Math.max(rect.left, 0);

        const visibleArea = visibleHeight * visibleWidth;
        const totalArea = rect.height * rect.width;

        return visibleArea / totalArea >= 0.5;
    }

    handleVideoVisibility() {
        this.videos.forEach((video) => {
            if (this.isMostlyVisible(video)) {
                this.playVideo(video);
            } else {
                this.pauseVideo(video);
            }
        });
    }

    async playVideo(video) {
        if (video.paused) {
            try {
                await video.play();
            } catch (error) {
                console.log("Autoplay prevented:", error);
            }
        }
    }

    pauseVideo(video) {
        if (!video.paused) {
            video.pause();
        }
    }

    pauseOtherVideos(currentVideo) {
        this.videos.forEach((video) => {
            if (video !== currentVideo && !video.paused) {
                video.pause();
            }
        });
    }

    // Public method to refresh videos (useful for dynamic content)
    refresh() {
        this.videos = document.querySelectorAll(".video-autoplay");
        this.init();
    }

    // Cleanup method
    destroy() {
        this.observers.forEach((observer) => observer.disconnect());
        this.observers.clear();

        window.removeEventListener("scroll", this.handleVideoVisibility);
        window.removeEventListener("resize", this.handleVideoVisibility);
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    window.videoAutoplay = new VideoAutoplay();
});

// Re-initialize for Livewire updates
document.addEventListener("livewire:navigated", () => {
    if (window.videoAutoplay) {
        window.videoAutoplay.destroy();
    }
    window.videoAutoplay = new VideoAutoplay();
});

// Re-initialize for Livewire component updates
document.addEventListener("livewire:updated", () => {
    if (window.videoAutoplay) {
        window.videoAutoplay.refresh();
    }
});
