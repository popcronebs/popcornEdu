function loadQuestion(index = 0) {
    const currentQuestion = quizData[index];
    const tryText = document.createElement('div');
    const button = document.createElement('button');
    button.className = 'check-button text-b-24px btn btn-danger w-100 rounded-4';
    button.textContent = "문제 채점하기";
    tryText.classList.add('try-text');
    tryText.textContent = `${currentQuestion?.questionNumber}번. ${currentQuestion?.question}`;
    answerContainer.innerHTML = currentQuestion?.choices?.map((choice, idx) => `
        <div class="quiz-answer-item ${userAnswers[index][0] && userAnswers[index][0].includes(idx + 1) ? "active" : ""}">
            <span class="quiz-answer-item-num">${idx + 1}</span>
            <span class="quiz-answer-item-text">${choice}</span>
        </div>
    `).join("");

    const quizAnswerItems = answerContainer.querySelectorAll(".quiz-answer-item");
    quizAnswerItems.forEach((item, idx) => {
        item.addEventListener("click", () => toggleAnswer(item, index, idx, userAnswers[index][0], (newAnswer) => {
            userAnswers[index][0] = newAnswer;
        }));
    });
    questionContainer.querySelector(".try-text")?.remove();
    questionContainer.prepend(tryText);
    arrowUpdate(index);
    updateLearningGrid(arrowNext.dataset.arrowNext - 1);
}

function toggleAnswer(item, questionIndex, choiceIndex, userAnswer, updateAnswerCallback) {
    const maxSelections = quizData[questionIndex].answer.length;
    const button = document.createElement('button');
    button.className = 'check-button text-b-24px btn btn-danger w-100 rounded-4 mb-2';
    button.textContent = "문제 채점하기";
    button.style.height = "72px";
    userAnswer = userAnswer || [];
    if (item.classList.contains("active")) {
        item.classList.remove("active");
        document.querySelectorAll(".learning-grid-item")[questionIndex].classList.remove("complete");
        userAnswer = userAnswer.filter(answer => answer !== choiceIndex + 1);
        if (userAnswer.length == 0) {
            userAnswer = null;
        }
        if (userAnswers.filter(answer => answer[0]).length != quizData.length) {
            document.querySelector(".check-button")?.remove();
        }
    } else if (userAnswer.length < maxSelections) {
        item.classList.add("active");
        userAnswer.push(choiceIndex + 1);
        userAnswer.sort((a, b) => a - b);
        if (userAnswer.length == quizData[questionIndex].answer.length) {
            document.querySelectorAll(".learning-grid-item")[questionIndex].classList.add("complete");
        }
        if (userAnswers.filter(answer => answer[0]).length == quizData.length) {
            btnWrap.prepend(button);
            const wrongQuestionIndex = quizData.reduce((acc, item, idx) => {
                if (!userAnswers[idx][0].every((answer, index) => answer == item.answer[index])) {
                    acc.push(idx);
                }
                return acc;
            }, []);
            document.querySelector(".check-button").addEventListener("click", () => {
                arrowPrev.dataset.arrowPrev = -1;
                arrowNext.dataset.arrowNext = 0;
                tryQuestion(wrongQuestionIndex[0]);
            });
        }
    }
    updateAnswerCallback(userAnswer);
}