const quizData = [
    {
        subject: "국어",
        grade: 2,
        semester: 1,
        unit: 7,
        lesson: 32,
        questionType: "기본문제",
        questionNumber: 1,
        question: "교실에서 어떤 일이 있었나요? (2개)",
        image: "#국어 2-1_32강_기본01_1.jpg",
        choices: [
            "주영이가 승진이의 가방은 만졌다.",
            "주영이가 승진이의 그림을 보고 놀랐다.",
            "주영이가 승진이에게 가방을 선물했다.",
            "주영이가 승진이에게 일부러 장난을 쳤다.",
            "주영이가 넘어져서 승진이가 그림을 망쳤다.",
        ],
        answer: [1, 5],
        explanation:
            "그림의 내용은 교실에서 주영이가 넘어져서 승진이의 그림을 망치는 장면입니다.",
        explanationLecture: "#국어_2-1_32강_기본01",
    },
    {
        subject: "국어",
        grade: 2,
        semester: 1,
        unit: 7,
        lesson: 32,
        questionType: "기본문제",
        questionNumber: 2,
        question: "승진이의 말을 들은 주영이의 마음으로 알맞은 것을 고르시오.",
        image: "#국어 2-1_32강_기본02_1.jpg",
        choices: [
            "자신의 생각을 정확하게 말하니까 이해가 잘 되고 재미있어.",
            "일부러 그런 것이 아닌데 큰 소리로 화를 내니까 당황스러워.",
            "내 실수에 화를 내는 모습을 보니 자랑스러웠어.",
            "입을 크게 벌리며 소리 지르는 모습이 너무 웃겼어.",
            "심심했는데 그림이 망가져 화를 내니 정말 기뻤어.",
        ],
        answer: [2],
        explanation:
            "주영이는 승진이가 화를 내는 모습에 놀라고 당황스러웠을 것입니다.",
        explanationLecture: "#국어_2-1_32강_기본02",
    },
    {
        subject: "국어",
        grade: 2,
        semester: 1,
        unit: 7,
        lesson: 32,
        questionType: "기본문제",
        questionNumber: 3,
        question:
            "밑줄 친 ㉠을 말할 때의 주영이의 표정으로 알맞은 것은 무엇입니까?",
        image: "#국어 2-1_32강_기본03_1.jpg",
        choices: [
            "슬픈 표정",
            "지루한 표정",
            "미안한 표정",
            "화가 난 표정",
            "자랑스러운 표정",
        ],
        answer: [3],
        explanation:
            "승진이의 그림을 망친 주영이는 미안한 표정으로 사과했습니다.",
        explanationLecture: "#국어_2-1_32강_기본03",
    },
];
const loadQuestion = (index) => {
    const currentQuestion = quizData[index];
    const questionContainer = document.querySelector(".quiz-question-view .quiz-question");
    const answerContainer = document.querySelector(".quiz-answer-view");
    const tryText = document.createElement("div");
    tryText.classList.add("try-text");
    tryText.textContent = `${currentQuestion.questionNumber}번. ${currentQuestion.question}`;

    answerContainer.innerHTML = currentQuestion.choices.map((choice, idx) => `
        <div class="quiz-answer-item ${userAnswers[index][0] && userAnswers[index][0].includes(idx + 1) ? "active" : ""}">
            <span class="quiz-answer-item-num">${idx + 1}</span>
            <span class="quiz-answer-item-text">${choice}</span>
        </div>
    `).join("");

    const quizAnswerItems = answerContainer.querySelectorAll(".quiz-answer-item");
    quizAnswerItems.forEach((item, idx) => {
        item.addEventListener("click", () => toggleAnswer(item, index, idx));
    });

    questionContainer.querySelector(".try-text")?.remove();
    questionContainer.prepend(tryText);
    updateLearningGrid(index);
    updateButtonStates(index);
};

const updateButtonStates = (index) => {
    document.querySelector(".quiz-answer-arrow-prev").disabled = index === 0;
};

const tryQuestion = (index) => {
    const currentQuestion = quizData[index];
    const questionContainer = document.querySelector(
        ".quiz-question-view .quiz-question"
    );
    const answerContainer = document.querySelector(".quiz-answer-view");
    const tryText = document.createElement("div");
    tryText.classList.add("try-text");
    tryText.textContent = `${currentQuestion.questionNumber}번. ${currentQuestion.question}`;

    const [beforeUserAnswers, beforeUserAnswers2] = userAnswers[index];

    answerContainer.innerHTML = currentQuestion.choices.map((choice, idx) => {
            const isWrong = beforeUserAnswers && beforeUserAnswers.includes(idx + 1);
            const isRetryActive = beforeUserAnswers2 && beforeUserAnswers2.includes(idx + 1);
            return `
            <div class="quiz-answer-item ${isWrong ? "wrong" : ""} ${isRetryActive ? "active" : ""}">
                <span class="quiz-answer-item-num">${idx + 1}</span>
                <span class="quiz-answer-item-text">${choice}</span>
            </div>
        `;
        })
        .join("");

    const commonElements = beforeUserAnswers.filter((element) =>
        currentQuestion.answer.includes(element)
    );
    commonElements.forEach((element) => {
        if (!beforeUserAnswers2.includes(element)) {
            beforeUserAnswers2.push(element);
        }
        const answerItem = answerContainer.querySelectorAll(".quiz-answer-item")[element - 1];
        answerItem.classList.remove("wrong");
        answerItem.classList.add("active", "answer-active");
    });

    answerContainer.querySelectorAll(".quiz-answer-item").forEach((item, idx) => {
        item.addEventListener("click", () =>
            toggleRetryAnswer(item, index, idx)
        );
    });

    questionContainer.querySelector(".try-text").remove();
    questionContainer.prepend(tryText);
    updateStarEffect(questionContainer);
    updateLearningGrid(index);
    updateButtonStates(index);
};

const toggleRetryAnswer = (item, questionIndex, choiceIndex) => {
    const maxSelections = quizData[questionIndex].answer.length;
    if (!userAnswers[questionIndex][1]) userAnswers[questionIndex][1] = [];
    if (item.classList.contains("active") && !item.classList.contains("answer-active")) {
        item.classList.remove("active");
        userAnswers[questionIndex][1] = userAnswers[questionIndex][1].filter((answer) => answer !== choiceIndex + 1);
    } else if (!item.classList.contains("wrong") && userAnswers[questionIndex][1].length < maxSelections) {
        item.classList.add("active");
        userAnswers[questionIndex][1].push(choiceIndex + 1);
    }
};

const resultQuestion = (index) => {
    const currentQuestion = quizData[index];
    const quizQuestionView = document.querySelector(".quiz-question-view");
    const questionContainer = quizQuestionView.querySelector(".quiz-question");
    const answerContainer = document.querySelector(".quiz-answer-view");
    const quizContent = document.querySelector(".quiz-cont");

    document.querySelector(".learning-list-body").style.display = "none";
    questionContainer.textContent = `${currentQuestion.questionNumber}번. ${currentQuestion.question}`;

    updateCircleEffect(quizQuestionView);
    resultAnswerContainer(currentQuestion, index, answerContainer);
    updateCommentary(currentQuestion, quizContent);
    updateScoreContainer();
    updateButtonStates(index);
};

const updateCircleEffect = (container) => {
    const existingCircleEffect = container.querySelector(".circle-effect");
    if (existingCircleEffect) existingCircleEffect.remove();
    const circleEffect = document.createElement("object");
    circleEffect.classList.add("circle-effect");
    circleEffect.setAttribute("data", "{{ asset('images/circle.svg') }}");
    circleEffect.setAttribute("type", "image/svg+xml");
    container.appendChild(circleEffect);
};

const updateStarEffect = (container) => {
    const existingStarEffect = container.querySelector(".star-effect");
    if (existingStarEffect) existingStarEffect.remove();
    const starEffect = document.createElement("object");
    starEffect.classList.add("star-effect");
    starEffect.setAttribute("data", "{{ asset('images/star.svg') }}");
    starEffect.setAttribute("type", "image/svg+xml");
    container.appendChild(starEffect);
};

const updateAnswerContainer = (question, index, container) => {
    container.innerHTML = question.choices
        .map((choice, idx) => {
            const isUserAnswer =
                userAnswers[index][0] &&
                userAnswers[index][0].includes(idx + 1);
            const isCorrectAnswer = question.answer.includes(idx + 1);
            return `
            <div class="quiz-answer-item ${isUserAnswer ? "active" : ""}">
                <span class="quiz-answer-item-num">
                ${
                    isCorrectAnswer
                        ? `
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 8.36977L8.16769 12.502C8.49364 12.9272 9.13035 12.9388 9.47154 12.5257L15 5.83301" stroke="#FF5065" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                `
                        : idx + 1
                }
                </span>
                <span class="quiz-answer-item-text ${
                    isCorrectAnswer ? "text-answer" : ""
                }">${choice}</span>
            </div>
        `;
        })
        .join("");
};

const resultAnswerContainer = (question, index, container) => {
    container.innerHTML = question.choices.map((choice, idx) => {
            const isUserAnswer = userAnswers[index][1] && userAnswers[index][1].includes(idx + 1);
            const isCorrectAnswer = question.answer.includes(idx + 1);
            return `
            <div class="quiz-answer-item ${isUserAnswer ? "active" : ""}">
                <span class="quiz-answer-item-num">
                ${
                    isCorrectAnswer ? `
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 8.36977L8.16769 12.502C8.49364 12.9272 9.13035 12.9388 9.47154 12.5257L15 5.83301" stroke="#FF5065" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                ` : idx + 1}
                </span>
                <span class="quiz-answer-item-text ${isCorrectAnswer ? "text-answer" : ""}">${choice}</span>
            </div>
        `;
        })
        .join("");
};

const updateCommentary = (question, container) => {
    const existingCommentary = container.querySelector(
        ".quiz-answer-commentary"
    );
    if (existingCommentary) existingCommentary.remove();
    const commentary = document.createElement("div");
    commentary.classList.add("quiz-answer-commentary");
    commentary.innerHTML = `
        <span>정답 : ${question.answer}번</span>
        <p>${question.explanation}</p>
    `;
    container.appendChild(commentary);
};

const updateScoreContainer = () => {
    const learningWrap = document.querySelector(".learning-wrap");
    const existingScoreBody = document.querySelector(".score-container");
    if (existingScoreBody) existingScoreBody.remove();

    const scoreContainer = document.createElement("div");
    scoreContainer.classList.add("score-container");
    const scoreBody = document.createElement("div");
    scoreBody.classList.add("score-body");
    const scoreGrid = document.createElement("div");
    scoreGrid.classList.add("score-grid");

    scoreGrid.innerHTML = quizData.map((question, idx) => `
        <div class="score-grid-row">
            <div class="score-grid-item"> 
                <span>${question.questionNumber}번</span>
                <span class="${JSON.stringify(question.answer) === JSON.stringify(userAnswers[idx][1]) ? "score-circle" : "score-stars"}">
                <span></span>
                </span>
            </div>
            <div class="score-grid-item">
                <span>도전</span>
                <span>
                <span>-</span>
                </span>
            </div>
        </div>
    `).join("");

    scoreBody.appendChild(scoreGrid);
    scoreContainer.appendChild(scoreBody);
    learningWrap.appendChild(scoreContainer);

    const scoreResultBody = document.createElement("div");
    scoreResultBody.classList.add("score-result-body");
    const correctCount = userAnswers.filter((answer, idx) => JSON.stringify(answer[0]) === JSON.stringify(quizData[idx].answer)).length;
    scoreResultBody.innerHTML = `
        <div class="score-result-title">
            <p class="subject-name">맞힌 문제</p>
            <p class="subject-score">${correctCount}개</p>
        </div>
        <div class="score-result-title">
            <p class="subject-name">틀린 문제</p>
            <p class="subject-score">${quizData.length - correctCount}개</p>
        </div>`;
    scoreBody.appendChild(scoreResultBody);
    const btnWrap = document.querySelector(".btn-wrap");
    const nextButton = document.createElement("button");
    nextButton.className = "text-b-24px btn btn-danger w-100 rounded-4 mb-3";
    nextButton.style.height = "72px";
    nextButton.textContent = "다음으로(테스트용)";
    nextButton.onclick = () => (window.location.href = "/student/study/summary");
    btnWrap.innerHTML = "";
    btnWrap.appendChild(nextButton);
};

document.addEventListener("DOMContentLoaded", function () {
    loadQuestion(currentQuestionIndex);
    let tryQuestionIndex = -1;
    let resultQuestionIndex = -1;
    let userAnswersProcessed = false;
    let userAnswersWrong = false;
    let incorrectQuestions = quizData.filter(
        (question, idx) =>
            JSON.stringify(question.answer) !==
            JSON.stringify(userAnswers[idx][0])
    );

    const prevButton = document.querySelector(".quiz-answer-arrow-prev");
    const nextButton = document.querySelector(".quiz-answer-arrow-next");

    prevButton.addEventListener("click", function () {
        if (tryQuestionIndex > 0 && resultQuestionIndex == -1) {
            tryQuestionIndex--;
            tryQuestion(wrongQuestionIndex[tryQuestionIndex]);
        } else if (
            resultQuestionIndex > 0 &&
            currentQuestionIndex == quizData.length - 1
        ) {
            resultQuestionIndex--;
            resultQuestion(resultQuestionIndex);
        } else if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            loadQuestion(currentQuestionIndex);
        }
    });

    nextButton.addEventListener("click", function () {
        if (!userAnswers[currentQuestionIndex][0] || userAnswers[currentQuestionIndex][0].length === 0 ||(quizData[currentQuestionIndex].answer.length > 1 && userAnswers[currentQuestionIndex][0].length < quizData[currentQuestionIndex].answer.length && currentQuestionIndex !== 1)) {
            alert("문제를 모두 체크해주세요.");
            return;
        }

        if (tryQuestionIndex >= 0 && userAnswers[tryQuestionIndex][1].length != quizData[tryQuestionIndex].answer.length) {
            alert("문제를 모두 체크해주세요.");
            return;
        }

        if (currentQuestionIndex < quizData.length - 1) {
            currentQuestionIndex++;
            loadQuestion(currentQuestionIndex);
        } else if (currentQuestionIndex === quizData.length - 1) {
            if (!userAnswersWrong) {
                wrongQuestionIndex = quizData.reduce((acc, question, idx) => {
                    if (
                        question.answer.some(
                            (answer) =>
                                !userAnswers[idx][0].includes(answer) &&
                                !userAnswers[idx][1].includes(answer)
                        )
                    ) {
                        acc.push(idx);
                    }
                    return acc;
                }, []);
                userAnswersWrong = true;
            }

            if (tryQuestionIndex < wrongQuestionIndex.length - 1) {
                tryQuestionIndex++;
                if (wrongQuestionIndex.length > tryQuestionIndex) {
                    if (!userAnswersProcessed) {
                        quizData.forEach((question, idx) => {
                            question.answer.forEach((answer) => {
                                if (
                                    userAnswers[idx][0].includes(answer) &&
                                    !userAnswers[idx][1].includes(answer)
                                ) {
                                    userAnswers[idx][1].push(answer);
                                }
                            });
                        });
                        userAnswersProcessed = true;
                    }
                    tryQuestion(wrongQuestionIndex[tryQuestionIndex]);
                }
            } else if (resultQuestionIndex < incorrectQuestions.length - 1) {
                resultQuestionIndex++;
                resultQuestion(resultQuestionIndex);
            }
        }
    });
});
