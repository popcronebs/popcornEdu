const userAnswers = Array(quizData.length).fill(null).map(() => [null, []]);
    let wrongQuestionIndex = [];

    function loadQuestion(index = 0) {
        const currentQuestion = quizData[index];
        const questionContainer = document.querySelector(".quiz-question-view .quiz-question");
        const answerContainer = document.querySelector(".quiz-answer-view");
        const learningGrid = document.querySelector(".learning-grid");
        const arrowPrev = document.querySelector(".quiz-answer-arrow-prev");
        const arrowNext = document.querySelector(".quiz-answer-arrow-next");
        const btnWrap = document.querySelector(".btn-wrap");
        const tryText = document.createElement('div');
        const button = document.createElement('button');
        button.className = 'check-button text-b-24px btn btn-danger w-100 rounded-4';
        button.textContent = "문제 채점하기";
        tryText.classList.add('try-text');
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
        arrowPrev.dataset.arrowPrev = index -1;
        arrowNext.dataset.arrowNext = index;
        arrowNext.dataset.arrowNext++
        arrowNext.disabled = arrowNext.dataset.arrowNext == quizData.length;
        arrowPrev.disabled = arrowPrev.dataset.arrowPrev == -1;
        
        if(arrowNext.dataset.arrowNext == quizData.length){
            btnWrap.append(button);
        }else{
            btnWrap.remove(".check-button");
        }
        updateLearningGrid(index);
    }

    function toggleAnswer(item, questionIndex, choiceIndex) {
        const maxSelections = quizData[questionIndex].answer.length;
        if (!userAnswers[questionIndex][0]) userAnswers[questionIndex][0] = [];

        if (item.classList.contains("active")) {
            item.classList.remove("active");
            userAnswers[questionIndex][0] = userAnswers[questionIndex][0].filter(
                (answer) => answer !== choiceIndex + 1
            );
        } else if (userAnswers[questionIndex][0].length < maxSelections) {
            item.classList.add("active");
            userAnswers[questionIndex][0].push(choiceIndex + 1);
        }
    }

    function updateLearningGrid(currentIndex) {
        const learningGrid = document.querySelector(".learning-grid");
        learningGrid.innerHTML = quizData
            .map(
                (question, idx) => `
                    <li class="learning-grid-item ${userAnswers[idx][0] ? "complete" : ""} ${
                                    idx === currentIndex ? "active" : ""
                                }">
                    <div class="learning-grid-item-title">
                        <p class="subject-name">${question.questionNumber}번 문제</p>
                    </div>
                    <div class="learning-grid-item-img">
                        <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="26" cy="26" r="26" fill="#FFF6E0" />
                        <path d="M18 23.3918L23.0683 30.0034C23.5898 30.6837 24.6086 30.7023 25.1545 30.0414L34 19.333" stroke="white" stroke-width="3" stroke-linecap="round" />
                        </svg>
                    </div>
                    </li>
                    `
            )
            .join("");
    }

    function updateButtonStates(index) {
        document.querySelector(".quiz-answer-arrow-prev").disabled = index === 0;
    }

    function tryQuestion(index) {
        const currentQuestion = quizData[index];
        const questionContainer = document.querySelector('.quiz-question-view .quiz-question');
        const answerContainer = document.querySelector('.quiz-answer-view');
        const learningGrid = document.querySelector('.learning-grid');
        const tryText = document.createElement('div');
        tryText.classList.add('try-text');
        // tryText.textContent = "재도전";
        tryText.textContent = `${currentQuestion.questionNumber}번. ${currentQuestion.question}`;
        let beforeUserAnswers = userAnswers[index][0];
        let beforeUserAnswers2 = userAnswers[index][1];

        answerContainer.innerHTML = currentQuestion.choices.map((choice, idx) => {
            const isWrong = beforeUserAnswers && beforeUserAnswers.includes(idx + 1);
            const isRetryActive = beforeUserAnswers2 && beforeUserAnswers2.includes(idx + 1);
            return `
                <div class="quiz-answer-item ${isWrong ? 'wrong' : ''} ${isRetryActive ? 'active' : ''}">
                    <span class="quiz-answer-item-num">${idx + 1}</span>
                    <span class="quiz-answer-item-text">${choice}</span>
                </div>
            `;
        }).join('');

        let commonElements = beforeUserAnswers.filter((element) => currentQuestion.answer.includes(element));
        commonElements.forEach((element) => {
            if (!beforeUserAnswers2.includes(element)) {
                beforeUserAnswers2.push(element);
            }
            answerContainer.querySelectorAll('.quiz-answer-item')[element - 1].classList.remove('wrong');
            answerContainer.querySelectorAll('.quiz-answer-item')[element - 1].classList.add('active');
            answerContainer.querySelectorAll('.quiz-answer-item')[element - 1].classList.add('answer-active');
        });



        answerContainer.querySelectorAll('.quiz-answer-item').forEach((item, idx) => {
            item.addEventListener("click", () => toggleRetryAnswer(item, index, idx));
        });

        questionContainer.querySelector(".try-text").remove();
        questionContainer.prepend(tryText);
        updateStarEffect(questionContainer);
        updateLearningGrid(index);
        updateButtonStates(index);
    }

    function toggleRetryAnswer(item, questionIndex, choiceIndex) {
        const maxSelections = quizData[questionIndex].answer.length;
        if (!userAnswers[questionIndex][1]) userAnswers[questionIndex][1] = [];
        if (item.classList.contains("active")) {
            if (!item.classList.contains("answer-active")) {
                item.classList.remove("active");
                userAnswers[questionIndex][1] = userAnswers[questionIndex][1].filter(
                    (answer) => answer !== choiceIndex + 1
                );
                userAnswers[questionIndex][1].map(answers => {
                    if (answers[1]) {
                        answers[1] = answers[1].filter(element => element !== choiceIndex + 1);
                    }
                    return answers;
                });
            }
        } else if (item.classList.contains("wrong")) {
            return
        } else if (userAnswers[questionIndex][1].length < maxSelections) {
            item.classList.add("active");
            userAnswers[questionIndex][1].push(choiceIndex + 1);
            console.log(userAnswers)
        }
    }

    function resultQuestion(index) {
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
    }


    function updateCircleEffect(container) {
        const existingCircleEffect = container.querySelector(".circle-effect");
        if (existingCircleEffect) existingCircleEffect.remove();
        const circleEffect = document.createElement("object");
        circleEffect.classList.add("circle-effect");
        circleEffect.setAttribute("data", "{{ asset('images/circle.svg') }}");
        circleEffect.setAttribute("type", "image/svg+xml");
        container.appendChild(circleEffect);
    }

    function updateStarEffect(container) {
        const existingStarEffect = container.querySelector(".star-effect");
        if (existingStarEffect) existingStarEffect.remove();
        const starEffect = document.createElement("object");
        starEffect.classList.add("star-effect");
        starEffect.setAttribute("data", "{{ asset('images/star.svg') }}");
        starEffect.setAttribute("type", "image/svg+xml");
        container.appendChild(starEffect);
    }

    function updateAnswerContainer(question, index, container) {
        container.innerHTML = question.choices.map((choice, idx) => {
                const isUserAnswer = userAnswers[index][0] && userAnswers[index][0].includes(idx + 1);
                const isCorrectAnswer = question.answer.includes(idx + 1);
                return `
                    <div class="quiz-answer-item ${isUserAnswer ? "active" : ""}">
                        <span class="quiz-answer-item-num">
                        ${
                            isCorrectAnswer ? `
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 8.36977L8.16769 12.502C8.49364 12.9272 9.13035 12.9388 9.47154 12.5257L15 5.83301" stroke="#FF5065" stroke-width="2" stroke-linecap="round"/>
                            </svg>` : idx + 1}
                        </span>
                        <span class="quiz-answer-item-text ${isCorrectAnswer ? "text-answer" : ""}">${choice}</span>
                    </div>
                `;
            })
            .join("");
    }

    function resultAnswerContainer(question, index, container) {
        container.innerHTML = question.choices
            .map((choice, idx) => {
                const isUserAnswer =
                    userAnswers[index][1] && userAnswers[index][1].includes(idx + 1);
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
            }).join("");
    }

    function updateCommentary(question, container) {
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
    }

    function updateScoreContainer() {
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
                      <span class="${
                          JSON.stringify(question.answer) ===
                          JSON.stringify(userAnswers[idx][1])
                              ? "score-circle"
                              : "score-stars"
                      }">
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
        scoreResultBody.innerHTML = `
            <div class="score-result-title">
                <p class="subject-name">맞힌 문제</p>
                <p class="subject-score">${
                    userAnswers.filter(
                        (answer, idx) =>
                            JSON.stringify(answer[0]) ===
                            JSON.stringify(quizData[idx].answer)
                    ).length
                }개</p>
            </div>
            <div class="score-result-title">
                <p class="subject-name">틀린 문제</p>
                <p class="subject-score">${
                    userAnswers.filter(
                        (answer, idx) =>
                            JSON.stringify(answer[0]) !==
                            JSON.stringify(quizData[idx].answer)
                    ).length
                }개</p>
            </div>`;

        scoreBody.appendChild(scoreResultBody);

        const btnWrap = document.querySelector(".btn-wrap");
        const nextButton = document.createElement("button");
        nextButton.className = "text-b-24px btn btn-danger w-100 rounded-4 mb-3";
        nextButton.style.height = "72px";
        nextButton.textContent = "다음으로(테스트용)";
        nextButton.onclick = () =>
            (window.location.href = "/student/study/summary");

        btnWrap.innerHTML = "";
        btnWrap.appendChild(nextButton);
    }

    document.addEventListener("DOMContentLoaded", function() {
        loadQuestion();
        let userAnswersProcessed = false;
        let userAnswersWrong = false;
        let incorrectQuestions = quizData.filter(
            (question, idx) =>
            JSON.stringify(question.answer) !== JSON.stringify(userAnswers[idx][0])
        );

        document.querySelector(".quiz-answer-arrow-prev").addEventListener("click", function() {
            loadQuestion(this.dataset.arrowPrev);
        });

        document.querySelector(".quiz-answer-arrow-next").addEventListener("click", function() {
            const currentAnswer = userAnswers[this.dataset.arrowNext - 1][0];
            const currentQuizAnswer = quizData[this.dataset.arrowNext - 1].answer;

            if (!currentAnswer || currentAnswer.length === 0 ||
                (currentQuizAnswer.length > 1 && currentAnswer.length < currentQuizAnswer.length && this.dataset.arrowNext !== 1)) {
                alert("문제를 모두 체크해주세요.");
                return;
            }

            if(quizData.length > this.dataset.arrowNext){
                loadQuestion(this.dataset.arrowNext);
            }
        });
    })