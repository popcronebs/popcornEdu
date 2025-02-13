const canvas = document.getElementById('memoCanvas');
const ctx = canvas.getContext('2d');
const memoToggleBtn = document.querySelector('.memo-toggle-btn');
const memoCanvasWrap = document.querySelector('.memo-canvas-wrap');
const quizContainer = document.querySelector('.quiz-container');
let isDrawing = false;


memoToggleBtn.addEventListener('click', () => {
    if (memoToggleBtn.classList.contains('active')) {
        memoToggleBtn.classList.remove('active');
        memoCanvasWrap.style.display = 'none';
    } else {
        memoToggleBtn.classList.add('active');
        memoCanvasWrap.style.display = 'block';
        resizeCanvas();
    }
});

canvas.addEventListener('mousedown', startDrawing);
canvas.addEventListener('mouseup', stopDrawing);
canvas.addEventListener('mousemove', draw);
canvas.addEventListener('touchstart', startDrawing, { passive: true });
canvas.addEventListener('touchend', stopDrawing, { passive: true });
canvas.addEventListener('touchmove', draw, { passive: true });
function resizeCanvas() {
    const tempCanvas = document.createElement('canvas');
    const tempCtx = tempCanvas.getContext('2d');
    tempCanvas.width = canvas.width;
    tempCanvas.height = canvas.height;
    tempCtx.drawImage(canvas, 0, 0);
    canvas.width = quizContainer.clientWidth;
    canvas.height = quizContainer.clientHeight;
    ctx.drawImage(tempCanvas, 0, 0);
}

// 초기 캔버스 크기 설정
resizeCanvas();

// 윈도우 크기 변경 시 캔버스 크기 조정
window.addEventListener('resize', resizeCanvas);


function startDrawing(e) {
    isDrawing = true;
    ctx.beginPath();
    ctx.moveTo(getX(e), getY(e));
}

function stopDrawing(e) {
    if (isDrawing) {
        ctx.stroke();
        ctx.closePath();
        isDrawing = false;
    }
}

function draw(e) {
    if (isDrawing) {
        const x = getX(e);
        const y = getY(e);
        ctx.lineWidth = 3
        ; 
        ctx.lineCap = 'round';
        ctx.lineTo(x, y);
        ctx.stroke();
    }
}

function getX(e) {
    const rect = canvas.getBoundingClientRect();
    if (e.touches && e.touches.length > 0) {
        const touch = e.touches[0];
        return touch.clientX - rect.left;
    } else {
        return e.clientX - rect.left;
    }
}

function getY(e) {
    const rect = canvas.getBoundingClientRect();
    if (e.touches && e.touches.length > 0) {
        const touch = e.touches[0];
        return touch.clientY - rect.top;
    } else {
        return e.clientY - rect.top;
    }
}