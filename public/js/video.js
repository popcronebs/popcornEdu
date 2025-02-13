const video = document.getElementById("lectureVideo");
const playPauseButton = document.getElementById("playPauseButton");
const progressBar = document.querySelector("#progressBar .progress-bar");
const currentTime = document.getElementById("currentTime");
const duration = document.getElementById("duration");
const fullscreenButton = document.getElementById("fullscreenButton");
const volumeBar = document.getElementById("volumeBar");
const playVolume = document.getElementById("playVolume");
const speedButton = document.getElementById("speedButton");
const speedSelect = document.getElementById("speedSelect");
const speedOptions = speedSelect.querySelectorAll("li");

const videoPlayIcon = document.querySelector(".video-play-icon");
const videoPauseIconImg = document.querySelector(".video-pause-icon-img");
const videoControlsPt = document.querySelector(".video-controls-pt");
let volumeBarValue = volumeBar.value;

// 디바이스 체크 변수 추가
const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
const isTablet = /iPad|Android/.test(navigator.userAgent) && !/Mobile/.test(navigator.userAgent);

let isFullscreen = false;
let controlsTimeout = null;
let lastPlayTime = 0;
const playbackTimeDiv = document.getElementById("playacc_time");
const playlastTime = document.querySelector("#playlast_time");
let accumulatedTime = 0;

// DOM 로드 이벤트 리스너
document.addEventListener("DOMContentLoaded", () => {
    VideoPlayer.init();
    video.addEventListener("play", () => {
        lastPlayTime = Date.now();
    });

    video.addEventListener("pause", updatePlaybackTime);

    video.addEventListener("ended", () => {
        updatePlaybackTime();
        studyVideoComplete();
    });
});


// 유틸리티 함수들
const formatTime = (time) => {
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60);
    return `${minutes}:${seconds.toString().padStart(2, "0")}`;
};

const updateVolumeBar = () => {
    const value = ((volumeBar.value - volumeBar.min) / (volumeBar.max - volumeBar.min)) * 100;
    volumeBar.style.setProperty("--value", `${value}%`);
    volumeBarValue = volumeBar.value;
};

const togglePlayPause = () => {
    if (video.paused) {
        video.play().then(() => {
            playPauseButton.innerHTML = `<img src="/images/pause_icon_w.svg" width="32">`;
            videoPauseIconImg.classList.add("active");
            lastPlayTime = Date.now();
            attendInsert();
        }).catch(error => {
            console.error("비디오 재생 실패:", error);
        });
    } else {
        video.pause();
        playPauseButton.innerHTML = '<img src="/images/video_play_icon2.svg" width="32">';
        videoPauseIconImg.classList.remove("active");
        updatePlaybackTime();
    }
};

// updatePlaybackTime 함수를 전역으로 이동
const updatePlaybackTime = () => {
    if (lastPlayTime) {
        accumulatedTime += (Date.now() - lastPlayTime) / 1000;
        lastPlayTime = 0;
        playbackTimeDiv.value = btoa(accumulatedTime.toFixed(2));
        playlastTime.value = btoa(video.currentTime.toFixed(2));
        studyVideoTimeUpdate();
    }
};

const VideoPlayer = {
    init() {
        this.setupEventListeners();
        this.setupTouchEvents();
        this.setupVolumeControl();
        this.setupSpeedControl();  
        this.setupPlayPauseEvents();
        this.setupAutoHideControls();
        this.setupBackgroundClick(); // 배경 클릭 이벤트 추가
        updateVolumeBar();
    },

    setupEventListeners() {
        // 비디오 상태 업데이트 이벤트
        video.addEventListener("timeupdate", this.updateVideoProgress);
        video.addEventListener("play", () => {
            playPauseButton.innerHTML = `<img src="/images/pause_icon_w.svg" width="32">`;
            videoPauseIconImg.classList.add("active");
            lastPlayTime = Date.now();
            attendInsert();
        });
        video.addEventListener("pause", () => {
            playPauseButton.innerHTML = '<img src="/images/video_play_icon2.svg" width="32">';
            videoPauseIconImg.classList.remove("active");
            updatePlaybackTime();
        });

        // 키보드 컨트롤
        document.addEventListener("keydown", this.handleKeyPress);
    },

    setupTouchEvents() {
        if (isMobile || isTablet) {
            let lastTap = 0;
            let touchStartTime = 0;
            let touchStartX = 0;
            let touchStartY = 0;
            let isTouchMove = false;
            let controlsVisible = false;

            const handleTouchStart = (e) => {
                touchStartTime = Date.now();
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
                isTouchMove = false;
                // 컨트롤의 현재 상태 저장
                controlsVisible = videoControlsPt.style.opacity !== "0";
            };

            const handleTouchMove = () => {
                isTouchMove = true;
            };

            const handleTouchEnd = (e) => {
                e.preventDefault();
                const touchEndTime = Date.now();
                const touchDuration = touchEndTime - touchStartTime;
                const touchEndX = e.changedTouches[0].clientX;
                const touchEndY = e.changedTouches[0].clientY;
                const touchDistance = Math.sqrt(
                    Math.pow(touchEndX - touchStartX, 2) + 
                    Math.pow(touchEndY - touchStartY, 2)
                );

                // 터치 이동이 없고 짧은 터치인 경우만 처리
                if (!isTouchMove && touchDuration < 300 && touchDistance < 10) {
                    const currentTime = Date.now();
                    const tapLength = currentTime - lastTap;
                    
                    if (tapLength < 300 && tapLength > 0) {
                        // 더블 탭: 전체화면 전환
                        this.toggleFullscreen();
                    } else {
                        // 싱글 탭: 컨트롤이 숨겨져 있으면 보이기만 하고,
                        // 보이는 상태에서는 재생/일시정지
                        if (!controlsVisible) {
                            this.toggleControls();
                        } else {
                            togglePlayPause();
                        }
                    }
                    lastTap = currentTime;
                } else if (isTouchMove) {
                    // 터치 이동이 있었던 경우는 컨트롤만 표시
                    this.toggleControls();
                }
            };

            // 비디오 영역 터치 이벤트
            [video, videoPlayIcon].forEach(element => {
                element.addEventListener('touchstart', handleTouchStart, { passive: false });
                element.addEventListener('touchmove', handleTouchMove, { passive: true });
                element.addEventListener('touchend', handleTouchEnd, { passive: false });
            });

            // 재생/일시정지 버튼은 항상 즉시 반응하도록 처리
            playPauseButton.addEventListener('touchend', (e) => {
                if (!isTouchMove) {
                    e.preventDefault();
                    togglePlayPause();
                }
            });

            // 전체화면 버튼도 항상 즉시 반응하도록 처리
            fullscreenButton.addEventListener('touchend', (e) => {
                if (!isTouchMove) {
                    e.preventDefault();
                    this.toggleFullscreen();
                }
            });
        }
    },

    setupPlayPauseEvents() {
        // 비디오 클릭/터치 이벤트
        [video, videoPlayIcon, playPauseButton].forEach(element => {
            // 데스크톱 클릭 이벤트
            if (!isMobile && !isTablet) {
                element.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (element === video) {
                        if (videoControlsPt.style.opacity === "0") {
                            this.toggleControls();
                        } else {
                            togglePlayPause();
                        }
                    } else {
                        togglePlayPause();
                    }
                });

                // 마우스 움직임 감지
                element.addEventListener('mousemove', () => {
                    this.toggleControls();
                });
            }
        });

        // 컨트롤바 표시 중 마우스가 벗어날 경우
        videoControlsPt.addEventListener('mouseleave', () => {
            if (!video.paused) {
                clearTimeout(controlsTimeout);
                controlsTimeout = setTimeout(() => {
                    videoPlayIcon.style.opacity = "0";
                    videoControlsPt.style.opacity = "0";
                }, 2000);
            }
        });
    },

    setupAutoHideControls() {
        let activityTimeout;
        let lastActivityTime = Date.now();

        const checkInactivity = () => {
            const currentTime = Date.now();
            const inactiveTime = currentTime - lastActivityTime;

            // 15초 동안 활동이 없고, 비디오가 재생 중일 때
            if (inactiveTime > 15000 && !video.paused) {
                videoPlayIcon.style.opacity = "0";
                videoControlsPt.style.opacity = "0";
            }
        };

        // 사용자 활동 감지
        const resetActivityTimer = () => {
            lastActivityTime = Date.now();
            clearTimeout(activityTimeout);
            
            // 컨트롤 표시
            if (!video.paused) {
                this.toggleControls();
            }

            // 새로운 비활성 타이머 시작
            activityTimeout = setInterval(checkInactivity, 1000);
        };

        // 마우스 움직임 감지
        document.addEventListener('mousemove', resetActivityTimer);
        document.addEventListener('keypress', resetActivityTimer);
        
        // 터치 이벤트 감지
        if (isMobile || isTablet) {
            document.addEventListener('touchstart', resetActivityTimer);
        }

        // 비디오 상태 변경 시 타이머 재설정
        video.addEventListener('play', resetActivityTimer);
        video.addEventListener('pause', () => {
            clearTimeout(activityTimeout);
            // 일시정지 시에는 컨트롤을 계속 표시
            videoPlayIcon.style.opacity = "1";
            videoControlsPt.style.opacity = "1";
        });

        // 초기 타이머 시작
        activityTimeout = setInterval(checkInactivity, 1000);
    },

    toggleControls() {
        clearTimeout(controlsTimeout);
        videoPlayIcon.style.opacity = "1";
        videoControlsPt.style.opacity = "1";

        if (!video.paused) {
            controlsTimeout = setTimeout(() => {
                videoPlayIcon.style.opacity = "0";
                videoControlsPt.style.opacity = "0";
            }, 3000);
        }
    },

    updateVideoProgress() {
        currentTime.textContent = formatTime(video.currentTime);
        progressBar.style.width = `${(video.currentTime / video.duration) * 100}%`;
        duration.textContent = formatTime(video.duration).replace(/NaN/gi, '--');
    },

    handleKeyPress(e) {
        switch(e.key.toLowerCase()) {
            case " ":
            case "k":
                e.preventDefault();
                togglePlayPause();
                break;
            case "f":
                e.preventDefault();
                VideoPlayer.toggleFullscreen();
                break;
            case "m":
                e.preventDefault();
                video.muted = !video.muted;
                break;
            case "arrowleft":
                e.preventDefault();
                video.currentTime -= 5;
                break;
            case "arrowright":
                e.preventDefault();
                video.currentTime += 5;
                break;
        }
    },

    toggleFullscreen() {
        const videoContainer = document.getElementById("videoPtDiv");
        
        if (!document.fullscreenElement) {
            if (videoContainer.requestFullscreen) {
                videoContainer.requestFullscreen();
            } else if (videoContainer.webkitRequestFullscreen) {
                videoContainer.webkitRequestFullscreen();
            }
            isFullscreen = true;
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            }
            isFullscreen = false;
        }
    },

    setupVolumeControl() {
        volumeBar.addEventListener("input", () => {
            video.volume = volumeBar.value;
            updateVolumeBar();
        });

        playVolume.addEventListener("click", () => {
            video.volume = video.volume === 0 ? volumeBarValue : 0;
            volumeBar.value = video.volume;
            updateVolumeBar();
        });
    },

    setupSpeedControl() {
        speedButton.addEventListener("click", () => {
            speedSelect.classList.toggle("show");
            clearTimeout(controlsTimeout);
        });

        speedOptions.forEach((option) => {
            option.addEventListener("click", () => {
                const speed = parseFloat(option.getAttribute("value"));
                video.playbackRate = speed;
                speedButton.querySelector("span").textContent = `${speed}`;
                speedOptions.forEach((opt) => 
                    opt.classList.toggle("active", opt === option)
                );
                speedSelect.classList.remove("show");
            });
        });

        // 스피드 선택 메뉴 외부 클릭시 닫기
        document.addEventListener("click", (event) => {
            if (!speedButton.contains(event.target) && !speedSelect.contains(event.target)) {
                speedSelect.classList.remove("show");
            }
        });
    },

    // 배경 클릭 이벤트 설정 추가
    setupBackgroundClick() {
        const videoContainer = document.getElementById("videoPtDiv");
        
        videoContainer.addEventListener('click', (e) => {
            // 비디오 컨테이너를 직접 클릭했을 때만 실행 (자식 요소 클릭은 제외)
            if (e.target === videoContainer) {
                if (videoControlsPt.style.opacity === "0") {
                    this.toggleControls();
                } else {
                    togglePlayPause();
                }
            }
        });
    }
};


// 전체화면 변경 이벤트 리스너
document.addEventListener('fullscreenchange', () => {
    isFullscreen = !!document.fullscreenElement;
});

// 키보드 단축키 이벤트
document.addEventListener("keydown", VideoPlayer.handleKeyPress);
