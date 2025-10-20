<?php
header('Content-Type: application/json');

// Create data directory if it doesn't exist
$dataDir = __DIR__ . '/data';
if (!file_exists($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['action'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$action = $input['action'];

try {
    if ($action === 'generate_listening') {
        $result = generateListeningActivity($input, $dataDir);
        echo json_encode(['success' => true, 'files' => $result]);
    } elseif ($action === 'generate_writing') {
        $result = generateWritingActivity($input, $dataDir);
        echo json_encode(['success' => true, 'files' => $result]);
    } elseif ($action === 'generate_dialogue') {
        $result = generateDialogueActivity($input, $dataDir);
        echo json_encode(['success' => true, 'files' => $result]);
    } elseif ($action === 'generate_speaking') {
        $result = generateSpeakingActivity($input, $dataDir);
        echo json_encode(['success' => true, 'files' => $result]);
    } elseif ($action === 'generate_wordclick') {
        $result = generateWordClickActivity($input, $dataDir);
        echo json_encode(['success' => true, 'files' => $result]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Unknown action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

function generateListeningActivity($data, $dataDir) {
    $timestamp = time();
    $activityDir = $dataDir . '/listening_' . $timestamp;
    mkdir($activityDir, 0755, true);
    
    $title = $data['title'];
    $description = $data['description'];
    $questions = $data['questions'];
    
    // Generate activity HTML
    $activityHtml = generateListeningHTML($title, $description, $questions);
    file_put_contents($activityDir . '/index.html', $activityHtml);
    
    // Generate slideshow for teacher
    $slideshowHtml = generateListeningSlideshowHTML($title, $questions);
    file_put_contents($activityDir . '/teacher_slideshow.html', $slideshowHtml);
    
    // Copy SCORM files
    $scormJs = file_get_contents(__DIR__ . '/templates/SCORMGeneric.js');
    file_put_contents($activityDir . '/SCORMGeneric.js', $scormJs);
    
    // Generate manifest
    $manifest = generateManifest($title, 'listening');
    file_put_contents($activityDir . '/imsmanifest.xml', $manifest);
    
    // Create ZIP
    $zipPath = $dataDir . '/listening_' . $timestamp . '.zip';
    createZip($activityDir, $zipPath);
    
    return [
        ['name' => 'SCORM Package (ZIP)', 'path' => 'data/listening_' . $timestamp . '.zip'],
        ['name' => 'Activity (HTML)', 'path' => 'data/listening_' . $timestamp . '/index.html'],
        ['name' => 'Teacher Slideshow', 'path' => 'data/listening_' . $timestamp . '/teacher_slideshow.html']
    ];
}

function generateWritingActivity($data, $dataDir) {
    $timestamp = time();
    $activityDir = $dataDir . '/writing_' . $timestamp;
    mkdir($activityDir, 0755, true);
    
    $title = $data['title'];
    $specialChars = $data['specialChars'];
    $sentences = $data['sentences'];
    $prompts = $data['prompts'];
    
    // Generate activity HTML
    $activityHtml = generateWritingHTML($title, $specialChars, $sentences);
    file_put_contents($activityDir . '/index.html', $activityHtml);
    
    // Generate slideshow for teacher
    $slideshowHtml = generateWritingSlideshowHTML($title, $prompts);
    file_put_contents($activityDir . '/teacher_slideshow.html', $slideshowHtml);
    
    // Copy SCORM files
    $scormJs = file_get_contents(__DIR__ . '/templates/SCORMGeneric.js');
    file_put_contents($activityDir . '/SCORMGeneric.js', $scormJs);
    
    // Generate manifest
    $manifest = generateManifest($title, 'writing');
    file_put_contents($activityDir . '/imsmanifest.xml', $manifest);
    
    // Create ZIP
    $zipPath = $dataDir . '/writing_' . $timestamp . '.zip';
    createZip($activityDir, $zipPath);
    
    return [
        ['name' => 'SCORM Package (ZIP)', 'path' => 'data/writing_' . $timestamp . '.zip'],
        ['name' => 'Activity (HTML)', 'path' => 'data/writing_' . $timestamp . '/index.html'],
        ['name' => 'Teacher Slideshow', 'path' => 'data/writing_' . $timestamp . '/teacher_slideshow.html']
    ];
}

function generateDialogueActivity($data, $dataDir) {
    $timestamp = time();
    $activityDir = $dataDir . '/dialogue_' . $timestamp;
    mkdir($activityDir, 0755, true);
    
    $title = $data['title'];
    $timerDuration = $data['timerDuration'];
    $dialogues = $data['dialogues'];
    
    // Generate activity HTML
    $activityHtml = generateDialogueHTML($title, $dialogues);
    file_put_contents($activityDir . '/index.html', $activityHtml);
    
    // Generate slideshow for teacher
    $slideshowHtml = generateDialogueSlideshowHTML($title, $timerDuration, $dialogues);
    file_put_contents($activityDir . '/teacher_slideshow.html', $slideshowHtml);
    
    // Copy SCORM files
    $scormJs = file_get_contents(__DIR__ . '/templates/SCORMGeneric.js');
    file_put_contents($activityDir . '/SCORMGeneric.js', $scormJs);
    
    // Generate manifest
    $manifest = generateManifest($title, 'dialogue');
    file_put_contents($activityDir . '/imsmanifest.xml', $manifest);
    
    // Create ZIP
    $zipPath = $dataDir . '/dialogue_' . $timestamp . '.zip';
    createZip($activityDir, $zipPath);
    
    return [
        ['name' => 'SCORM Package (ZIP)', 'path' => 'data/dialogue_' . $timestamp . '.zip'],
        ['name' => 'Activity (HTML)', 'path' => 'data/dialogue_' . $timestamp . '/index.html'],
        ['name' => 'Teacher Slideshow', 'path' => 'data/dialogue_' . $timestamp . '/teacher_slideshow.html']
    ];
}

function generateSpeakingActivity($data, $dataDir) {
    $timestamp = time();
    $activityDir = $dataDir . '/speaking_' . $timestamp;
    mkdir($activityDir, 0755, true);
    
    $title = $data['title'];
    $timerDuration = $data['timerDuration'];
    $exercises = $data['exercises'];
    
    // Generate activity HTML
    $activityHtml = generateSpeakingHTML($title, $exercises);
    file_put_contents($activityDir . '/index.html', $activityHtml);
    
    // Generate slideshow for teacher
    $slideshowHtml = generateSpeakingSlideshowHTML($title, $timerDuration, $exercises);
    file_put_contents($activityDir . '/teacher_slideshow.html', $slideshowHtml);
    
    // Copy SCORM files
    $scormJs = file_get_contents(__DIR__ . '/templates/SCORMGeneric.js');
    file_put_contents($activityDir . '/SCORMGeneric.js', $scormJs);
    
    // Generate manifest
    $manifest = generateManifest($title, 'speaking');
    file_put_contents($activityDir . '/imsmanifest.xml', $manifest);
    
    // Create ZIP
    $zipPath = $dataDir . '/speaking_' . $timestamp . '.zip';
    createZip($activityDir, $zipPath);
    
    return [
        ['name' => 'SCORM Package (ZIP)', 'path' => 'data/speaking_' . $timestamp . '.zip'],
        ['name' => 'Activity (HTML)', 'path' => 'data/speaking_' . $timestamp . '/index.html'],
        ['name' => 'Teacher Slideshow', 'path' => 'data/speaking_' . $timestamp . '/teacher_slideshow.html']
    ];
}

function generateWordClickActivity($data, $dataDir) {
    $timestamp = time();
    $activityDir = $dataDir . '/wordclick_' . $timestamp;
    mkdir($activityDir, 0755, true);
    
    $title = $data['title'];
    $timerDuration = $data['timerDuration'];
    $exercises = $data['exercises'];
    
    // Generate activity HTML
    $activityHtml = generateWordClickHTML($title, $exercises);
    file_put_contents($activityDir . '/index.html', $activityHtml);
    
    // Generate slideshow for teacher
    $slideshowHtml = generateWordClickSlideshowHTML($title, $timerDuration, $exercises);
    file_put_contents($activityDir . '/teacher_slideshow.html', $slideshowHtml);
    
    // Copy SCORM files
    $scormJs = file_get_contents(__DIR__ . '/templates/SCORMGeneric.js');
    file_put_contents($activityDir . '/SCORMGeneric.js', $scormJs);
    
    // Generate manifest
    $manifest = generateManifest($title, 'wordclick');
    file_put_contents($activityDir . '/imsmanifest.xml', $manifest);
    
    // Create ZIP
    $zipPath = $dataDir . '/wordclick_' . $timestamp . '.zip';
    createZip($activityDir, $zipPath);
    
    return [
        ['name' => 'SCORM Package (ZIP)', 'path' => 'data/wordclick_' . $timestamp . '.zip'],
        ['name' => 'Activity (HTML)', 'path' => 'data/wordclick_' . $timestamp . '/index.html'],
        ['name' => 'Teacher Slideshow', 'path' => 'data/wordclick_' . $timestamp . '/teacher_slideshow.html']
    ];
}

function generateListeningHTML($title, $description, $questions) {
    $questionsJson = json_encode($questions);
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>$title</title>
    <script src="SCORMGeneric.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin: 0;
            background: white;
            padding: 1rem;
        }
        .title {
            background-color: #667eea;
            color: white;
            font-size: 1.6rem;
            font-weight: bold;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
        }
        .exercise-container {
            width: 90%;
            max-width: 800px;
            background: #fff;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            border: 1px solid #667eea;
        }
        .screen {
            display: none;
            width: 100%;
        }
        .screen.active {
            display: block;
        }
        #start-btn {
            background-color: #667eea;
            color: white;
            font-size: 1.3rem;
            font-weight: 600;
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
            box-shadow: 0 6px 15px rgba(102, 126, 234, 0.3);
            text-align: center;
        }
        .question-counter {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 20px;
        }
        .progress-bar {
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            margin-bottom: 30px;
            overflow: hidden;
            width: 100%;
        }
        .progress-fill {
            height: 100%;
            background: #667eea;
            border-radius: 4px;
            transition: width 0.3s ease;
            width: 0%;
        }
        .answers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            width: 100%;
        }
        .answer-btn {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            font-size: 1rem;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s;
        }
        .answer-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .answer-btn.selected {
            background: #667eea;
            color: white;
            border-color: transparent;
        }
        .score-display {
            text-align: center;
            margin-bottom: 30px;
        }
        .score-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
        }
        .score-total {
            font-size: 1.2rem;
            color: #666;
        }
        .results-details {
            margin-bottom: 30px;
            width: 100%;
        }
        .result-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .result-question {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .result-answer {
            color: #666;
        }
        .result-incorrect {
            background: #fee;
        }
        .result-correct {
            background: #efe;
        }
    </style>
</head>
<body onload="initSCORM()" onunload="terminateSCORM()">
    <div class="exercise-container">
        <div id="start-screen" class="screen active">
            <h1 class="title">$title</h1>
            <p style="text-align: center; font-size: 1.1rem; color: #666; margin-bottom: 2rem;">
                $description
            </p>
            <button id="start-btn">Start Activity</button>
        </div>
        <div id="question-screen" class="screen">
            <div class="question-counter">
                <span id="current-question">1</span> / <span id="total-questions">5</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <div id="answers-container" class="answers-grid"></div>
        </div>
        <div id="results-screen" class="screen">
            <h2 class="title">Activity Complete!</h2>
            <div class="score-display">
                <span id="score-text">Your Score:</span>
                <span id="score-value" class="score-value">0</span>
                <span class="score-total">/ 100</span>
            </div>
            <div id="results-details" class="results-details"></div>
        </div>
    </div>
    <script>
        const questions = $questionsJson;
        let currentQuestionIndex = 0;
        let userAnswers = [];
        let score = 0;
        
        const startScreen = document.getElementById('start-screen');
        const questionScreen = document.getElementById('question-screen');
        const resultsScreen = document.getElementById('results-screen');
        const startBtn = document.getElementById('start-btn');
        const answersContainer = document.getElementById('answers-container');
        const currentQuestionEl = document.getElementById('current-question');
        const totalQuestionsEl = document.getElementById('total-questions');
        const progressFill = document.querySelector('.progress-fill');
        const scoreValue = document.getElementById('score-value');
        const resultsDetails = document.getElementById('results-details');
        
        function initSCORM() {
            if (SCOInitialize() === "true") {
                objAPI.LMSSetValue("cmi.core.lesson_status", "incomplete");
            }
        }
        
        function terminateSCORM() {
            SCOFinish();
        }
        
        function updateSCORMScore(score) {
            if (APIOK()) {
                objAPI.LMSSetValue("cmi.core.score.raw", score.toString());
                const passingThreshold = 70;
                const successStatus = score >= passingThreshold ? "passed" : "failed";
                objAPI.LMSSetValue("cmi.core.lesson_status", successStatus);
                objAPI.LMSCommit("");
            }
        }
        
        startBtn.addEventListener('click', startActivity);
        
        function startActivity() {
            startScreen.classList.remove('active');
            questionScreen.classList.add('active');
            currentQuestionIndex = 0;
            userAnswers = [];
            score = 0;
            showQuestion();
        }
        
        function showQuestion() {
            const question = questions[currentQuestionIndex];
            currentQuestionEl.textContent = currentQuestionIndex + 1;
            totalQuestionsEl.textContent = questions.length;
            const progress = ((currentQuestionIndex + 1) / questions.length) * 100;
            progressFill.style.width = progress + '%';
            
            answersContainer.innerHTML = '';
            question.answers.forEach((answer, index) => {
                const button = document.createElement('button');
                button.className = 'answer-btn';
                button.textContent = answer;
                button.addEventListener('click', () => selectAnswer(index));
                answersContainer.appendChild(button);
            });
        }
        
        function selectAnswer(answerIndex) {
            userAnswers.push(answerIndex);
            const buttons = document.querySelectorAll('.answer-btn');
            buttons.forEach(btn => btn.classList.remove('selected'));
            buttons[answerIndex].classList.add('selected');
            
            setTimeout(() => {
                currentQuestionIndex++;
                if (currentQuestionIndex < questions.length) {
                    showQuestion();
                } else {
                    showResults();
                }
            }, 500);
        }
        
        function showResults() {
            questionScreen.classList.remove('active');
            resultsScreen.classList.add('active');
            score = 0;
            const pointsPerQuestion = 100 / questions.length;
            
            questions.forEach((question, index) => {
                if (userAnswers[index] === question.correct) score += pointsPerQuestion;
            });
            
            scoreValue.textContent = Math.round(score);
            updateSCORMScore(Math.round(score));
            
            resultsDetails.innerHTML = '';
            questions.forEach((question, index) => {
                const resultItem = document.createElement('div');
                resultItem.className = 'result-item';
                if (userAnswers[index] !== question.correct) {
                    resultItem.classList.add('result-incorrect');
                    resultItem.innerHTML = `
                        <div class="result-question">Question \${index + 1}: \${question.question}</div>
                        <div class="result-answer">Your answer: \${question.answers[userAnswers[index]]}</div>
                        <div class="result-answer">Correct answer: \${question.answers[question.correct]}</div>
                    `;
                } else {
                    resultItem.classList.add('result-correct');
                    resultItem.innerHTML = `
                        <div class="result-question">Question \${index + 1}: \${question.question}</div>
                        <div class="result-answer">Correct! ✓</div>
                    `;
                }
                resultsDetails.appendChild(resultItem);
            });
        }
    </script>
</body>
</html>
HTML;
}

function generateWritingHTML($title, $specialChars, $sentences) {
    $sentencesJson = json_encode($sentences);
    $charsButtons = implode('', array_map(function($char) {
        return "<button class=\"char-button\" onclick=\"insertChar('$char')\">$char</button>";
    }, $specialChars));
    
    $sentenceCount = count($sentences);
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title</title>
    <script src="SCORMGeneric.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin: 0;
            background: white;
            padding: 1rem;
        }
        .title {
            background-color: #667eea;
            color: white;
            font-size: 1.6rem;
            font-weight: bold;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
        }
        .exercise-container {
            width: 90%;
            max-width: 800px;
            background: #fff;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            border: 1px solid #667eea;
        }
        .score-display {
            width: 100%;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        .score-bar {
            width: 100%;
            height: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            overflow: hidden;
        }
        .score-fill {
            height: 100%;
            background-color: #4CAF50;
            width: 0%;
            transition: width 0.3s;
        }
        .sentence-container {
            width: 100%;
            display: none;
        }
        .sentence-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .input-wrapper {
            width: 100%;
            margin-bottom: 1rem;
        }
        input {
            width: 100%;
            padding: 0.75rem;
            font-size: 24px;
            border-radius: 5px;
            border: 1px solid #667eea;
            box-sizing: border-box;
        }
        .feedback-display {
            width: 100%;
            min-height: 32px;
            margin-bottom: 1rem;
            font-size: 24px;
            line-height: 1.5;
            text-align: left;
        }
        .correct-char {
            color: #4CAF50;
        }
        .incorrect-char {
            color: #f44336;
        }
        .character-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
            width: 100%;
            justify-content: center;
        }
        .character-bar button {
            padding: 10px 12px;
            font-size: 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: auto;
            margin-top: 0;
            min-width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .character-bar button:hover {
            background: #5a67d8;
        }
        .navigation-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            width: 100%;
        }
        .nav-button {
            padding: 0.75rem;
            font-size: 16px;
            background: #667eea;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .nav-button:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body onload="initSCORM()" onunload="terminateSCORM()">
    <div class="title">$title</div>
    <div class="exercise-container">
        <div class="score-display">
            Score: <span id="score">0</span> / 100
            <div class="score-bar">
                <div class="score-fill" id="scoreBar" style="width: 0%"></div>
            </div>
        </div>
        <div id="sentencesContainer"></div>
        <div class="character-bar">
            $charsButtons
        </div>
        <div class="navigation-buttons">
            <button class="nav-button" id="prevBtn" onclick="previousSentence()">Previous</button>
            <span style="display: flex; align-items: center; font-weight: bold; color: #555;">
                <span id="currentSentence">1</span> / $sentenceCount
            </span>
            <button class="nav-button" id="nextBtn" onclick="nextSentence()">Next</button>
        </div>
    </div>
    <script>
        const expectedSentences = $sentencesJson;
        const sentenceCount = $sentenceCount;
        let scores = new Array(sentenceCount).fill(0);
        let maxScore = 100;
        let pointsPerQuestion = maxScore / sentenceCount;
        let activeInput = null;
        let currentSentenceIndex = 0;
        let hasCompletedActivity = false;
        
        function initSCORM() {
            if (SCOInitialize() === "true") {
                objAPI.LMSSetValue("cmi.core.lesson_status", "incomplete");
            }
        }
        
        function terminateSCORM() {
            SCOFinish();
        }
        
        function updateSCORMScore(score) {
            if (APIOK()) {
                objAPI.LMSSetValue("cmi.core.score.raw", score.toString());
                const passingThreshold = 70;
                const successStatus = score >= passingThreshold ? "passed" : "failed";
                objAPI.LMSSetValue("cmi.core.lesson_status", successStatus);
                objAPI.LMSCommit("");
            }
        }
        
        function initializeActivity() {
            const container = document.getElementById('sentencesContainer');
            container.innerHTML = '';
            for (let i = 0; i < sentenceCount; i++) {
                const sentenceDiv = document.createElement('div');
                sentenceDiv.className = 'sentence-container';
                sentenceDiv.id = `sentence-\${i}`;
                sentenceDiv.style.display = i === 0 ? 'block' : 'none';
                sentenceDiv.innerHTML = `
                    <div class="sentence-label">Sentence \${i + 1}</div>
                    <div class="input-wrapper">
                        <input type="text" class="sentence-input" id="input\${i}" placeholder="Type sentence \${i + 1} here..." onfocus="activeInput = \${i}" oninput="checkSentence(\${i})" onpaste="return false" oncopy="return false">
                    </div>
                    <div class="feedback-display" id="feedback\${i}"></div>
                `;
                container.appendChild(sentenceDiv);
            }
            updateNavigationButtons();
        }
        
        function showSentence(index) {
            for (let i = 0; i < sentenceCount; i++) {
                const sentenceDiv = document.getElementById(`sentence-\${i}`);
                sentenceDiv.style.display = i === index ? 'block' : 'none';
            }
            currentSentenceIndex = index;
            document.getElementById('currentSentence').textContent = index + 1;
            updateNavigationButtons();
            const currentInput = document.getElementById(`input\${index}`);
            if (currentInput) {
                currentInput.focus();
                activeInput = index;
            }
        }
        
        function nextSentence() {
            if (currentSentenceIndex < sentenceCount - 1) {
                showSentence(currentSentenceIndex + 1);
            }
        }
        
        function previousSentence() {
            if (currentSentenceIndex > 0) {
                showSentence(currentSentenceIndex - 1);
            }
        }
        
        function updateNavigationButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            prevBtn.disabled = currentSentenceIndex === 0;
            nextBtn.disabled = currentSentenceIndex === sentenceCount - 1;
        }
        
        function checkAllSentencesCompleted() {
            for (let i = 0; i < sentenceCount; i++) {
                const input = document.getElementById(`input\${i}`);
                if (!input.value || input.value.length < expectedSentences[i].length) {
                    return false;
                }
            }
            return true;
        }
        
        function checkSentence(index) {
            const input = document.getElementById(`input\${index}`);
            const feedback = document.getElementById(`feedback\${index}`);
            const expected = expectedSentences[index];
            const userText = input.value;
            let feedbackHTML = '';
            let correctChars = 0;
            for (let i = 0; i < userText.length; i++) {
                if (i < expected.length && userText[i] === expected[i]) {
                    feedbackHTML += `<span class="correct-char">\${userText[i]}</span>`;
                    correctChars++;
                } else {
                    feedbackHTML += `<span class="incorrect-char">\${userText[i]}</span>`;
                }
            }
            feedback.innerHTML = feedbackHTML;
            if (userText.length >= expected.length) {
                const accuracy = correctChars / expected.length;
                scores[index] = accuracy === 1 ? pointsPerQuestion : (pointsPerQuestion * accuracy * 0.8);
            } else {
                scores[index] = (correctChars / expected.length) * pointsPerQuestion * 0.9;
            }
            updateTotalScore();
            
            // Check if all sentences are completed and update SCORM
            if (!hasCompletedActivity && checkAllSentencesCompleted()) {
                hasCompletedActivity = true;
                // Force final score calculation
                setTimeout(() => {
                    updateTotalScore();
                }, 100);
            }
        }
        
        function updateTotalScore() {
            const totalScore = scores.reduce((sum, score) => sum + score, 0);
            document.getElementById('score').textContent = Math.round(totalScore);
            document.getElementById('scoreBar').style.width = totalScore + '%';
            updateSCORMScore(Math.round(totalScore));
        }
        
        function insertChar(char) {
            if (activeInput !== null) {
                const input = document.getElementById(`input\${activeInput}`);
                const start = input.selectionStart;
                const end = input.selectionEnd;
                const text = input.value;
                input.value = text.substring(0, start) + char + text.substring(end);
                input.focus();
                input.setSelectionRange(start + 1, start + 1);
                checkSentence(activeInput);
            }
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'ArrowLeft' && currentSentenceIndex > 0) {
                previousSentence();
            } else if (event.key === 'ArrowRight' && currentSentenceIndex < sentenceCount - 1) {
                nextSentence();
            }
        });
        
        window.onload = function() {
            initializeActivity();
        };
    </script>
</body>
</html>
HTML;
}

function generateDialogueHTML($title, $dialogues) {
    $dialoguesJson = json_encode($dialogues);
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title</title>
    <script src="SCORMGeneric.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin: 0;
            background: white;
            padding: 1rem;
        }
        .title {
            background-color: #667eea;
            color: white;
            font-size: 1.6rem;
            font-weight: bold;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
        }
        .exercise-container {
            width: 90%;
            max-width: 800px;
            background: #fff;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            border: 1px solid #667eea;
        }
        #score-display {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #333;
            width: 100%;
            text-align: center;
        }
        .image-container {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0;
            width: 100%;
        }
        .character-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .speech-bubble {
            background: #f0f0f0;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            min-width: 200px;
            max-width: 300px;
            opacity: 0;
            transition: opacity 0.3s;
            min-height: 50px;
        }
        .clickable-image {
            cursor: pointer;
            position: relative;
        }
        .clickable-image.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            border: 3px solid #667eea;
        }
        .person1-avatar {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .person2-avatar {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .button-container {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
            width: 100%;
            justify-content: center;
        }
        .control-button {
            padding: 0.75rem 1.5rem;
            font-size: 16px;
            background: #667eea;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .control-button:hover {
            background: #5a67d8;
        }
        #questions {
            display: none;
            width: 100%;
        }
        .question {
            margin-bottom: 1.5rem;
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
        }
        .question-text {
            font-weight: bold;
            margin-bottom: 0.75rem;
        }
        .answer-choices {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .answer-btn {
            padding: 0.75rem;
            font-size: 14px;
            background: white;
            color: #333;
            border: 2px solid #e0e0e0;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s;
            text-align: left;
        }
        .answer-btn:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }
        .answer-btn.correct {
            background: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }
        .answer-btn.incorrect {
            background: #f44336;
            color: white;
            border-color: #f44336;
        }
        .answer-btn:disabled {
            cursor: not-allowed;
        }
        .replay-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ff4444;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }
        .word {
            opacity: 0.3;
            transition: opacity 0.1s;
        }
        .highlight {
            opacity: 1;
            font-weight: bold;
            color: #667eea;
        }
        .completion-screen {
            display: none;
            text-align: center;
        }
        .completion-screen.active {
            display: block;
        }
        .final-score {
            font-size: 3rem;
            color: #667eea;
            font-weight: bold;
            margin: 2rem 0;
        }
    </style>
</head>
<body onload="initSCORM()" onunload="terminateSCORM()">
    <h1 class="title">$title</h1>
    <div class="exercise-container">
        <div id="main-activity">
            <div id="score-display">Score: 0 / 100</div>
            <div class="image-container">
                <div class="character-container">
                    <div class="speech-bubble" id="bubble1"></div>
                    <div class="clickable-image" id="image1">
                        <div class="avatar person1-avatar">👤</div>
                    </div>
                </div>
                <div class="character-container">
                    <div class="speech-bubble" id="bubble2"></div>
                    <div class="clickable-image" id="image2">
                        <div class="avatar person2-avatar">👥</div>
                    </div>
                </div>
            </div>
            <div class="button-container">
                <button id="start-questions-button" class="control-button" style="display: none;">Start Questions</button>
                <button id="next-button" class="control-button" style="display: none;">Next →</button>
            </div>
            <div id="questions"></div>
        </div>
        <div id="completion-screen" class="completion-screen">
            <h2>Activity Complete!</h2>
            <div class="final-score" id="final-score">0 / 100</div>
            <p>Great job!</p>
        </div>
    </div>
    <script>
        const dialogues = $dialoguesJson;
        let currentDialogueIndex = 0;
        let currentStep = 0;
        let image1Replayed = false;
        let image2Replayed = false;
        let questionsStarted = false;
        let totalScore = 0;
        const pointsPerQuestion = 10;
        
        const image1 = document.getElementById('image1');
        const image2 = document.getElementById('image2');
        const bubble1 = document.getElementById('bubble1');
        const bubble2 = document.getElementById('bubble2');
        const nextButton = document.getElementById('next-button');
        const startQuestionsButton = document.getElementById('start-questions-button');
        const questionsDiv = document.getElementById('questions');
        const scoreDisplay = document.getElementById('score-display');
        
        function initSCORM() {
            if (SCOInitialize() === "true") {
                objAPI.LMSSetValue("cmi.core.lesson_status", "incomplete");
            }
        }
        
        function terminateSCORM() {
            SCOFinish();
        }
        
        function updateSCORMScore(score) {
            if (APIOK()) {
                objAPI.LMSSetValue("cmi.core.score.raw", score.toString());
                const passingThreshold = 70;
                const successStatus = score >= passingThreshold ? "passed" : "failed";
                objAPI.LMSSetValue("cmi.core.lesson_status", successStatus);
                objAPI.LMSCommit("");
            }
        }
        
        function speakText(text, bubble) {
            bubble.innerHTML = '';
            const words = text.split(' ');
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'es-MX';
            utterance.rate = 0.9;
            words.forEach((word) => {
                const span = document.createElement('span');
                span.textContent = word + ' ';
                span.className = 'word';
                bubble.appendChild(span);
            });
            let wordIndex = 0;
            utterance.onboundary = (event) => {
                if (wordIndex < words.length) {
                    const wordSpan = bubble.children[wordIndex];
                    if (wordSpan) {
                        wordSpan.style.opacity = '1';
                        wordSpan.classList.add('highlight');
                        if (wordIndex > 0 && bubble.children[wordIndex - 1]) {
                            bubble.children[wordIndex - 1].classList.remove('highlight');
                        }
                    }
                    wordIndex++;
                }
            };
            utterance.onend = () => {
                if (wordIndex > 0 && wordIndex <= words.length && bubble.children[wordIndex - 1]) {
                    bubble.children[wordIndex - 1].classList.remove('highlight');
                }
                checkCompletion();
            };
            speechSynthesis.speak(utterance);
        }
        
        function handleImageClick(imageElement, bubbleElement) {
            const dialogue = dialogues[currentDialogueIndex];
            const isImage1 = imageElement.id === 'image1';
            const isReplayed = isImage1 ? image1Replayed : image2Replayed;
            
            if (isReplayed || imageElement.classList.contains('disabled')) return;
            
            if (currentStep >= dialogue.lines.length) {
                if (questionsStarted) return;
                
                const lineIndex = isImage1 ? 0 : 1;
                const line = dialogue.lines[lineIndex];
                bubbleElement.style.opacity = '1';
                speakText(line.text, bubbleElement);
                
                const badge = document.createElement('div');
                badge.className = 'replay-badge';
                badge.textContent = '1';
                imageElement.appendChild(badge);
                
                if (isImage1) {
                    image1Replayed = true;
                } else {
                    image2Replayed = true;
                }
                imageElement.classList.add('disabled');
                return;
            }
            
            const line = dialogue.lines[currentStep];
            if ((line.person === 1 && imageElement.id !== 'image1') || 
                (line.person === 2 && imageElement.id !== 'image2')) return;
            
            bubbleElement.style.opacity = '1';
            speakText(line.text, bubbleElement);
            currentStep++;
        }
        
        function checkCompletion() {
            const dialogue = dialogues[currentDialogueIndex];
            if (currentStep >= dialogue.lines.length) {
                startQuestionsButton.style.display = 'block';
            }
        }
        
        function showQuestions(questions) {
            questionsDiv.style.display = 'block';
            questionsDiv.innerHTML = '<h3>Preguntas sobre el diálogo:</h3>';
            
            questions.forEach((question, qIndex) => {
                const questionDiv = document.createElement('div');
                questionDiv.className = 'question';
                questionDiv.innerHTML = \`
                    <div class="question-text">\${qIndex + 1}. \${question.text}</div>
                    <div class="answer-choices" id="choices-\${qIndex}"></div>
                \`;
                questionsDiv.appendChild(questionDiv);
                
                const choicesContainer = questionDiv.querySelector(\`#choices-\${qIndex}\`);
                question.choices.forEach((choice, cIndex) => {
                    const btn = document.createElement('button');
                    btn.className = 'answer-btn';
                    btn.textContent = choice;
                    btn.addEventListener('click', () => handleAnswerClick(btn, cIndex, question.correct, qIndex));
                    choicesContainer.appendChild(btn);
                });
            });
        }
        
        function handleAnswerClick(button, selectedIndex, correctIndex, questionIndex) {
            const allButtons = button.parentElement.querySelectorAll('.answer-btn');
            allButtons.forEach(btn => btn.disabled = true);
            
            if (selectedIndex === correctIndex) {
                button.classList.add('correct');
                totalScore = Math.min(totalScore + pointsPerQuestion, 100);
            } else {
                button.classList.add('incorrect');
                allButtons[correctIndex].classList.add('correct');
                totalScore = Math.max(totalScore - 5, 0);
            }
            updateScoreDisplay();
        }
        
        function updateScoreDisplay() {
            scoreDisplay.textContent = \`Score: \${totalScore} / 100\`;
            updateSCORMScore(totalScore);
        }
        
        function resetDialogue() {
            speechSynthesis.cancel();
            currentStep = 0;
            image1Replayed = false;
            image2Replayed = false;
            questionsStarted = false;
            bubble1.style.opacity = '0';
            bubble2.style.opacity = '0';
            bubble1.innerHTML = '';
            bubble2.innerHTML = '';
            image1.classList.remove('disabled');
            image2.classList.remove('disabled');
            
            const badge1 = image1.querySelector('.replay-badge');
            const badge2 = image2.querySelector('.replay-badge');
            if (badge1) badge1.remove();
            if (badge2) badge2.remove();
            
            startQuestionsButton.style.display = 'none';
            nextButton.style.display = 'none';
            questionsDiv.style.display = 'none';
        }
        
        function nextDialogue() {
            currentDialogueIndex++;
            if (currentDialogueIndex >= dialogues.length) {
                document.getElementById('main-activity').style.display = 'none';
                const completionScreen = document.getElementById('completion-screen');
                completionScreen.classList.add('active');
                document.getElementById('final-score').textContent = \`\${totalScore} / 100\`;
                return;
            }
            resetDialogue();
        }
        
        function startQuestions() {
            questionsStarted = true;
            image1.classList.add('disabled');
            image2.classList.add('disabled');
            bubble1.style.opacity = '0';
            bubble2.style.opacity = '0';
            bubble1.innerHTML = '';
            bubble2.innerHTML = '';
            startQuestionsButton.style.display = 'none';
            nextButton.style.display = 'block';
            
            const dialogue = dialogues[currentDialogueIndex];
            showQuestions(dialogue.questions);
        }
        
        image1.addEventListener('click', () => handleImageClick(image1, bubble1));
        image2.addEventListener('click', () => handleImageClick(image2, bubble2));
        startQuestionsButton.addEventListener('click', startQuestions);
        nextButton.addEventListener('click', nextDialogue);
        
        resetDialogue();
    </script>
</body>
</html>
HTML;
}

function generateSpeakingHTML($title, $exercises) {
    $exercisesJson = json_encode($exercises);
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="SCORMGeneric.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin: 0;
            background: white;
            padding: 1rem;
        }
        .title {
            background-color: #667eea;
            color: white;
            font-size: 1.6rem;
            font-weight: bold;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1000px;
        }
        .exercise-container {
            width: 90%;
            max-width: 1000px;
            background: #fff;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            border: 1px solid #667eea;
        }
        .dialogue-wrapper {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .image-container {
            width: 100%;
            display: flex;
            justify-content: center;
        }
        .dialogue-section {
            width: 100%;
            display: flex;
            gap: 2rem;
            justify-content: space-between;
            align-items: flex-end;
        }
        .character-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        .character-side.left {
            align-items: flex-start;
            padding-left: 1rem;
        }
        .character-side.right {
            align-items: flex-end;
            padding-right: 1rem;
        }
        .transcription-box {
            width: 90%;
            padding: 1rem;
            border-radius: 0.5rem;
            font-size: 1.1rem;
            visibility: hidden;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 2px solid #ddd;
            gap: 0.5rem;
        }
        .transcription-box.left {
            background-color: #e3f2fd;
            border-color: #667eea;
        }
        .transcription-box.right {
            background-color: #f1f1f1;
            border-color: #667eea;
        }
        .transcription-box.correct {
            background-color: #c8e6c9;
            border-color: #4caf50;
            color: #2e7d32;
            font-weight: bold;
        }
        .transcription-box.incorrect {
            background-color: #ffcdd2;
            border-color: #f44336;
            color: #c62828;
            font-weight: bold;
        }
        .feedback-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .mic-button {
            width: 60px;
            height: 60px;
            background-color: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }
        .mic-button:hover {
            background-color: #5a67d8;
            transform: scale(1.1);
        }
        .mic-button:active {
            transform: scale(0.95);
        }
        .mic-button i {
            font-size: 1.8rem;
        }
        #instructions {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        .exercise-image {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        #score-display {
            margin-top: 2rem;
            font-size: 1.2rem;
            text-align: center;
        }
        .completion-screen {
            display: none;
            text-align: center;
        }
        .completion-screen.active {
            display: block;
        }
        .final-score {
            font-size: 3rem;
            color: #667eea;
            font-weight: bold;
            margin: 2rem 0;
        }
    </style>
</head>
<body onload="initSCORM()" onunload="terminateSCORM()">
    <div class="title">$title</div>
    <div id="instructions"></div>
    <div class="exercise-container" id="main-activity">
        <div class="dialogue-wrapper">
            <div class="image-container">
                <img alt="Dialogue" class="exercise-image" id="exercise-image" src="" style="display: none;"/>
            </div>
            <div class="dialogue-section">
                <div class="character-side left">
                    <div class="transcription-box left" id="transcription-left"></div>
                    <button class="mic-button" id="mic-left">
                        <i class="fas fa-microphone"></i>
                    </button>
                </div>
                <div class="character-side right">
                    <div class="transcription-box right" id="transcription-right"></div>
                    <button class="mic-button" id="mic-right">
                        <i class="fas fa-microphone"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="completion-screen" class="completion-screen">
        <h2>Activity Complete!</h2>
        <div class="final-score" id="final-score">0 / 100</div>
        <p>Great job!</p>
    </div>
    <script>
        const exercises = $exercisesJson;
        const totalScore = 100;
        let currentDialogueIndex = 0;
        let activeExerciseIndex = 0;
        let score = 0;
        let totalQuestions = exercises.reduce((total, exercise) => total + exercise.dialogue.length, 0);
        let activityCompleted = false;

        function initSCORM() {
            if (SCOInitialize() === "true") {
                objAPI.LMSSetValue("cmi.core.lesson_status", "incomplete");
            }
        }
        
        function terminateSCORM() {
            SCOFinish();
        }
        
        function updateSCORMScore(score) {
            if (APIOK()) {
                objAPI.LMSSetValue("cmi.core.score.raw", score.toString());
                const passingThreshold = 70;
                const successStatus = score >= passingThreshold ? "passed" : "failed";
                objAPI.LMSSetValue("cmi.core.lesson_status", successStatus);
                objAPI.LMSCommit("");
            }
        }

        function updateExercise() {
            if (activeExerciseIndex >= exercises.length) {
                finishActivity();
                return;
            }
            const ex = exercises[activeExerciseIndex];
            document.getElementById('instructions').textContent = ex.instructions;
            const imageEl = document.getElementById('exercise-image');
            if (ex.image) {
                imageEl.src = ex.image;
                imageEl.style.display = 'block';
            } else {
                imageEl.style.display = 'none';
            }
            const leftBox = document.getElementById('transcription-left');
            const rightBox = document.getElementById('transcription-right');
            leftBox.innerHTML = '';
            leftBox.style.visibility = 'hidden';
            leftBox.classList.remove('correct', 'incorrect');
            rightBox.innerHTML = '';
            rightBox.style.visibility = 'hidden';
            rightBox.classList.remove('correct', 'incorrect');
            currentDialogueIndex = 0;
        }

        function nextDialogue() {
            currentDialogueIndex++;
            if (currentDialogueIndex >= exercises[activeExerciseIndex].dialogue.length) {
                activeExerciseIndex++;
                updateExercise();
            }
        }

        function finishActivity() {
            if (activityCompleted) return;
            activityCompleted = true;
            let finalScore = Math.round((score / totalQuestions) * totalScore);
            document.getElementById('main-activity').style.display = 'none';
            document.getElementById('instructions').style.display = 'none';
            const completionScreen = document.getElementById('completion-screen');
            completionScreen.classList.add('active');
            document.getElementById('final-score').textContent = finalScore + ' / ' + totalScore;
            updateSCORMScore(finalScore);
        }

        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            document.getElementById('instructions').textContent = 'Speech recognition not supported in this browser. Please try using Google Chrome.';
        } else {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const recognition = new SpeechRecognition();
            recognition.lang = 'es-MX';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript.toLowerCase();
                if (activeExerciseIndex < exercises.length && currentDialogueIndex < exercises[activeExerciseIndex].dialogue.length) {
                    const currentDialogue = exercises[activeExerciseIndex].dialogue[currentDialogueIndex];
                    const boxId = currentDialogue.side === 'left' ? 'transcription-left' : 'transcription-right';
                    const box = document.getElementById(boxId);
                    const correctAnswers = currentDialogue.answers;
                    const isCorrect = correctAnswers.some(answer => transcript.includes(answer.toLowerCase()));
                    
                    box.innerHTML = '';
                    const textSpan = document.createElement('span');
                    textSpan.textContent = transcript;
                    box.appendChild(textSpan);
                    
                    const icon = document.createElement('i');
                    icon.className = 'feedback-icon';
                    if (isCorrect) {
                        icon.className += ' fas fa-check';
                        box.classList.add('correct');
                        box.classList.remove('incorrect');
                    } else {
                        icon.className += ' fas fa-times';
                        box.classList.add('incorrect');
                        box.classList.remove('correct');
                    }
                    box.appendChild(icon);
                    box.style.visibility = "visible";
                    
                    if (isCorrect) {
                        score++;
                        setTimeout(() => {
                            nextDialogue();
                        }, 1500);
                    } else {
                        setTimeout(() => {
                            recognition.start();
                        }, 1000);
                    }
                }
            };

            recognition.onerror = function(event) {
                console.error('Error occurred in recognition:', event.error);
            };

            recognition.onend = function() {
                if (activeExerciseIndex < exercises.length && !activityCompleted) {
                    // Wait for button click
                }
            };

            document.getElementById('mic-left').addEventListener('click', () => {
                if (activeExerciseIndex < exercises.length && currentDialogueIndex < exercises[activeExerciseIndex].dialogue.length) {
                    if (exercises[activeExerciseIndex].dialogue[currentDialogueIndex].side === 'left') {
                        recognition.start();
                    }
                }
            });

            document.getElementById('mic-right').addEventListener('click', () => {
                if (activeExerciseIndex < exercises.length && currentDialogueIndex < exercises[activeExerciseIndex].dialogue.length) {
                    if (exercises[activeExerciseIndex].dialogue[currentDialogueIndex].side === 'right') {
                        recognition.start();
                    }
                }
            });

            updateExercise();
        }
    </script>
</body>
</html>
HTML;
}

function generateWordClickHTML($title, $exercises) {
    $exercisesJson = json_encode($exercises);
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>$title</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
<script src="SCORMGeneric.js"></script>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        min-height: 100vh;
        margin: 0;
        background: white;
        padding: 1rem;
    }
    .title {
        background-color: #667eea;
        color: white;
        font-size: 1.6rem;
        font-weight: bold;
        padding: 1rem 2rem;
        border-radius: 0.5rem;
        margin-top: 1rem;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 90%;
        max-width: 800px;
    }
    .exercise-container {
        width: 90%;
        max-width: 800px;
        background: #fff;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        margin: 2rem auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
        border: 1px solid #667eea;
    }
    .exercise-counter {
        font-size: 1rem;
        color: #666;
        margin-bottom: 0.5rem;
    }
    .progress {
        width: 100%;
        height: 10px;
        background-color: #e0e0e0;
        border-radius: 5px;
        margin-bottom: 1rem;
    }
    .progress-bar {
        height: 100%;
        background-color: #667eea;
        border-radius: 5px;
        width: 0%;
        transition: width 0.3s ease;
    }
    .sentence {
        font-size: 1.5rem;
        text-align: center;
        margin: 1rem 0;
        min-height: 50px;
    }
    .verb {
        color: #667eea;
        cursor: pointer;
        text-decoration: underline;
        font-weight: bold;
    }
    .verb.correct {
        color: #4CAF50;
        cursor: default;
        text-decoration: none;
    }
    .verb.incorrect {
        color: #f44336;
        cursor: default;
        text-decoration: none;
    }
    .feedback {
        min-height: 24px;
        font-size: 1rem;
        margin: 1rem 0;
    }
    .feedback.correct {
        color: #4CAF50;
    }
    .feedback.incorrect {
        color: #f44336;
    }
    button {
        margin: 0.5rem;
        padding: 0.75rem;
        font-size: 16px;
        background: #667eea;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        width: auto;
        min-width: 120px;
    }
    button:hover {
        background: #5a67d8;
    }
    button:disabled {
        background: #cccccc;
        cursor: not-allowed;
    }
    .text-center {
        display: flex;
        justify-content: center;
        gap: 1rem;
        width: 100%;
    }
    .completion-screen {
        display: none;
        text-align: center;
    }
    .completion-screen.active {
        display: block;
    }
    .final-score {
        font-size: 3rem;
        color: #667eea;
        font-weight: bold;
        margin: 2rem 0;
    }
</style>
</head>
<body onload="initSCORM()" onunload="terminateSCORM()">
    <div class="title">$title</div>

    <div class="exercise-container" id="main-activity">
        <div class="exercise-counter">
            Exercise <span id="currentExercise">1</span> of <span id="totalExercises">10</span>
        </div>

        <div class="progress">
            <div class="progress-bar" id="progressBar" role="progressbar"></div>
        </div>

        <div class="sentence" id="sentence"></div>
        <div class="feedback" id="feedback"></div>

        <div class="text-center">
            <button id="checkBtn"><i class="fas fa-check"></i> Check Answer</button>
            <button id="nextBtn" style="display: none;"><i class="fas fa-arrow-right"></i> Next Exercise</button>
        </div>
    </div>

    <div id="completion-screen" class="completion-screen">
        <h2>Activity Complete!</h2>
        <div class="final-score" id="final-score">0 / 100</div>
        <p>Great job!</p>
    </div>

<script>
    const exercises = $exercisesJson;
    let currentExercise = 0;
    let score = 0;
    let currentVerbs = [];
    let activityCompleted = false;

    function initSCORM() {
        if (SCOInitialize() === "true") {
            objAPI.LMSSetValue("cmi.core.lesson_status", "incomplete");
        }
    }
    
    function terminateSCORM() {
        SCOFinish();
    }
    
    function updateSCORMScore(score) {
        if (APIOK()) {
            objAPI.LMSSetValue("cmi.core.score.raw", score.toString());
            const passingThreshold = 70;
            const successStatus = score >= passingThreshold ? "passed" : "failed";
            objAPI.LMSSetValue("cmi.core.lesson_status", successStatus);
            objAPI.LMSCommit("");
        }
    }

    function init() {
        document.getElementById('totalExercises').textContent = exercises.length;
        loadExercise();
    }

    function loadExercise() {
        const ex = exercises[currentExercise];
        document.getElementById('currentExercise').textContent = currentExercise + 1;
        document.getElementById('feedback').textContent = '';
        document.getElementById('feedback').className = 'feedback';
        const progress = ((currentExercise + 1) / exercises.length) * 100;
        document.getElementById('progressBar').style.width = progress + '%';

        let sentence = ex.sentence;
        currentVerbs = [];

        ex.verbs.forEach((v, i) => {
            const span = \`<span class="verb" data-index="\${i}">\${v.text}</span>\`;
            sentence = sentence.replace(\`{\${v.text}}\`, span);
            currentVerbs.push(v);
        });
        document.getElementById('sentence').innerHTML = sentence;
        document.getElementById('nextBtn').style.display = 'none';
        document.getElementById('checkBtn').disabled = false;
        attachVerbHandlers();
    }

    function attachVerbHandlers() {
        document.querySelectorAll('.verb').forEach(verbEl => {
            verbEl.addEventListener('click', function() {
                const index = parseInt(this.dataset.index);
                const v = currentVerbs[index];
                const currentText = this.textContent;
                const currentIndex = v.options.indexOf(currentText);
                const nextText = v.options[(currentIndex + 1) % v.options.length];
                this.textContent = nextText;
            });
        });
    }

    function checkAnswers() {
        let correctAll = true;
        document.querySelectorAll('.verb').forEach(verbEl => {
            const index = parseInt(verbEl.dataset.index);
            const v = currentVerbs[index];
            if (verbEl.textContent === v.correct) {
                verbEl.classList.add('correct');
                score += 5;
            } else {
                verbEl.classList.add('incorrect');
                score = Math.max(score - 3, 0);
                correctAll = false;
            }
            verbEl.style.pointerEvents = 'none';
        });

        const feedback = document.getElementById('feedback');
        if (correctAll) {
            feedback.textContent = '¡Correcto!';
            feedback.classList.add('correct');
        } else {
            feedback.textContent = 'Algunas respuestas son incorrectas.';
            feedback.classList.add('incorrect');
        }

        document.getElementById('nextBtn').style.display = 'block';
        document.getElementById('checkBtn').disabled = true;
    }

    function nextExercise() {
        currentExercise++;
        if (currentExercise < exercises.length) {
            document.getElementById('checkBtn').disabled = false;
            loadExercise();
        } else {
            finishActivity();
        }
    }

    function finishActivity() {
        if (activityCompleted) return;
        activityCompleted = true;
        const finalScore = Math.min(Math.max(score, 0), 100);
        document.getElementById('main-activity').style.display = 'none';
        const completionScreen = document.getElementById('completion-screen');
        completionScreen.classList.add('active');
        document.getElementById('final-score').textContent = finalScore + ' / 100';
        updateSCORMScore(finalScore);
    }

    document.getElementById('checkBtn').addEventListener('click', checkAnswers);
    document.getElementById('nextBtn').addEventListener('click', nextExercise);
    init();
</script>
</body>
</html>
HTML;
}

function generateDialogueSlideshowHTML($title, $timerDuration, $dialogues) {
    $instructionsHtml = '';
    foreach ($dialogues as $index => $dialogue) {
        $num = $index + 1;
        $linesHtml = implode('<br>', array_map(function($line) {
            $person = $line['person'] === 1 ? 'Person 1' : 'Person 2';
            return "<strong>$person:</strong> " . htmlspecialchars($line['text']);
        }, $dialogue['lines']));
        
        $instructionsHtml .= <<<HTML
        <div class="dialogue-section">
            <h3>Dialogue $num</h3>
            <div class="dialogue-lines">
                $linesHtml
            </div>
        </div>
HTML;
    }
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title - Teacher Guide</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1a1a1a;
            color: white;
            padding: 2rem;
            line-height: 1.8;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
            color: #667eea;
        }
        .timer-section {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 3rem;
        }
        .timer-display {
            font-size: 4rem;
            font-weight: bold;
            color: #667eea;
            margin: 1rem 0;
            font-family: 'Courier New', monospace;
        }
        .timer-controls {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1rem;
        }
        .timer-btn {
            padding: 1rem 2rem;
            font-size: 1.2rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .timer-btn:hover {
            background: #5a67d8;
        }
        .timer-btn.reset {
            background: #444;
        }
        .timer-btn.reset:hover {
            background: #666;
        }
        .instructions {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
            color: #aaa;
        }
        .dialogue-section {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border-left: 4px solid #667eea;
        }
        .dialogue-section h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        .dialogue-lines {
            font-size: 1.3rem;
            line-height: 2;
        }
        .dialogue-lines strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>$title</h1>
        
        <div class="timer-section">
            <h2>Activity Timer</h2>
            <div class="timer-display" id="timer">00:00</div>
            <div class="timer-controls">
                <button class="timer-btn" id="startBtn">Start</button>
                <button class="timer-btn" id="pauseBtn">Pause</button>
                <button class="timer-btn reset" id="resetBtn">Reset</button>
            </div>
        </div>
        
        <div class="instructions">
            Students will listen to and interact with the following dialogues:
        </div>
        
        $instructionsHtml
    </div>
    
    <script>
        let totalSeconds = $timerDuration;
        let remainingSeconds = totalSeconds;
        let timerInterval = null;
        let isRunning = false;
        
        const timerDisplay = document.getElementById('timer');
        const startBtn = document.getElementById('startBtn');
        const pauseBtn = document.getElementById('pauseBtn');
        const resetBtn = document.getElementById('resetBtn');
        
        function updateDisplay() {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            timerDisplay.textContent = 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
            
            if (remainingSeconds <= 10 && remainingSeconds > 0) {
                timerDisplay.style.color = '#ff4444';
            } else if (remainingSeconds === 0) {
                timerDisplay.style.color = '#ff0000';
                timerDisplay.textContent = 'TIME UP!';
                stopTimer();
                playAlert();
            } else {
                timerDisplay.style.color = '#667eea';
            }
        }
        
        function startTimer() {
            if (isRunning) return;
            isRunning = true;
            timerInterval = setInterval(() => {
                if (remainingSeconds > 0) {
                    remainingSeconds--;
                    updateDisplay();
                } else {
                    stopTimer();
                }
            }, 1000);
        }
        
        function stopTimer() {
            isRunning = false;
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
        }
        
        function resetTimer() {
            stopTimer();
            remainingSeconds = totalSeconds;
            updateDisplay();
        }
        
        function playAlert() {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 800;
            oscillator.type = 'sine';
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        }
        
        startBtn.addEventListener('click', startTimer);
        pauseBtn.addEventListener('click', stopTimer);
        resetBtn.addEventListener('click', resetTimer);
        
        updateDisplay();
    </script>
</body>
</html>
HTML;
}

function generateListeningSlideshowHTML($title, $questions) {
    $slides = array_map(function($q, $i) {
        $num = $i + 1;
        $question = htmlspecialchars($q['question']);
        $answers = implode('', array_map(function($a, $j) {
            $letter = chr(65 + $j);
            return "<li>$letter. " . htmlspecialchars($a) . "</li>";
        }, $q['answers'], array_keys($q['answers'])));
        
        return <<<HTML
        <div class="slide">
            <div class="question-number">Question $num</div>
            <div class="question-text">$question</div>
            <ul class="answer-list">
                $answers
            </ul>
        </div>
HTML;
    }, $questions, array_keys($questions));
    
    $slidesHtml = implode("\n", $slides);
    $totalSlides = count($questions);
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title - Teacher Slideshow</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1a1a1a;
            color: white;
            overflow: hidden;
        }
        .slideshow-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .slide {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 3rem;
            max-width: 1000px;
            width: 90%;
        }
        .slide.active {
            display: flex;
        }
        .question-number {
            font-size: 1.5rem;
            color: #667eea;
            margin-bottom: 2rem;
            font-weight: 600;
        }
        .question-text {
            font-size: 3rem;
            margin-bottom: 3rem;
            line-height: 1.4;
        }
        .answer-list {
            list-style: none;
            font-size: 2rem;
            text-align: left;
            width: 100%;
        }
        .answer-list li {
            margin-bottom: 1.5rem;
            padding: 1rem 2rem;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }
        .controls {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        .control-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
        }
        .control-btn:hover {
            background: #5a67d8;
        }
        .control-btn:disabled {
            background: #444;
            cursor: not-allowed;
        }
        .slide-counter {
            font-size: 1.2rem;
            padding: 0 1rem;
        }
    </style>
</head>
<body>
    <div class="slideshow-container">
        $slidesHtml
    </div>
    <div class="controls">
        <button class="control-btn" id="prevBtn">← Previous</button>
        <span class="slide-counter">
            <span id="currentSlide">1</span> / $totalSlides
        </span>
        <button class="control-btn" id="nextBtn">Next →</button>
    </div>
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const currentSlideEl = document.getElementById('currentSlide');
        
        function showSlide(n) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[n].classList.add('active');
            currentSlideEl.textContent = n + 1;
            prevBtn.disabled = n === 0;
            nextBtn.disabled = n === totalSlides - 1;
        }
        
        function nextSlide() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                showSlide(currentSlide);
            }
        }
        
        function prevSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                showSlide(currentSlide);
            }
        }
        
        prevBtn.addEventListener('click', prevSlide);
        nextBtn.addEventListener('click', nextSlide);
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight' || e.key === ' ') {
                nextSlide();
            } else if (e.key === 'ArrowLeft') {
                prevSlide();
            }
        });
        
        showSlide(0);
    </script>
</body>
</html>
HTML;
}

function generateWritingSlideshowHTML($title, $prompts) {
    $promptsList = implode('', array_map(function($p, $i) {
        $num = $i + 1;
        return "<li>$num. " . htmlspecialchars($p) . "</li>";
    }, $prompts, array_keys($prompts)));
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title - Teacher Guide</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1a1a1a;
            color: white;
            padding: 3rem;
            line-height: 1.8;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 3rem;
            text-align: center;
            color: #667eea;
        }
        .instructions {
            font-size: 1.8rem;
            margin-bottom: 3rem;
            text-align: center;
            color: #aaa;
        }
        .sentences-list {
            list-style: none;
            font-size: 2rem;
        }
        .sentences-list li {
            margin-bottom: 2rem;
            padding: 1.5rem 2rem;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>$title</h1>
        <div class="instructions">
            Students should complete these sentences with the correct verb forms:
        </div>
        <ul class="sentences-list">
            $promptsList
        </ul>
    </div>
</body>
</html>
HTML;
}

function generateSpeakingSlideshowHTML($title, $timerDuration, $exercises) {
    $instructionsHtml = '';
    foreach ($exercises as $index => $exercise) {
        $num = $index + 1;
        $instructions = htmlspecialchars($exercise['instructions']);
        
        $dialogueHtml = '';
        foreach ($exercise['dialogue'] as $part) {
            $side = $part['side'] === 'left' ? 'Left Speaker' : 'Right Speaker';
            $answers = implode(', ', array_map('htmlspecialchars', $part['answers']));
            $dialogueHtml .= "<div><strong>$side:</strong> $answers</div>";
        }
        
        $instructionsHtml .= <<<HTML
        <div class="exercise-section">
            <h3>Exercise $num</h3>
            <p class="exercise-instructions">$instructions</p>
            <div class="dialogue-answers">
                $dialogueHtml
            </div>
        </div>
HTML;
    }
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title - Teacher Guide</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1a1a1a;
            color: white;
            padding: 2rem;
            line-height: 1.8;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
            color: #667eea;
        }
        .timer-section {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 3rem;
        }
        .timer-display {
            font-size: 4rem;
            font-weight: bold;
            color: #667eea;
            margin: 1rem 0;
            font-family: 'Courier New', monospace;
        }
        .timer-controls {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1rem;
        }
        .timer-btn {
            padding: 1rem 2rem;
            font-size: 1.2rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .timer-btn:hover {
            background: #5a67d8;
        }
        .timer-btn.reset {
            background: #444;
        }
        .timer-btn.reset:hover {
            background: #666;
        }
        .instructions {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
            color: #aaa;
        }
        .exercise-section {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border-left: 4px solid #667eea;
        }
        .exercise-section h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        .exercise-instructions {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            font-style: italic;
        }
        .dialogue-answers {
            font-size: 1.2rem;
            line-height: 2;
        }
        .dialogue-answers div {
            margin-bottom: 0.5rem;
        }
        .dialogue-answers strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>$title</h1>
        
        <div class="timer-section">
            <h2>Activity Timer</h2>
            <div class="timer-display" id="timer">00:00</div>
            <div class="timer-controls">
                <button class="timer-btn" id="startBtn">Start</button>
                <button class="timer-btn" id="pauseBtn">Pause</button>
                <button class="timer-btn reset" id="resetBtn">Reset</button>
            </div>
        </div>
        
        <div class="instructions">
            Students will speak the following phrases (acceptable answers shown):
        </div>
        
        $instructionsHtml
    </div>
    
    <script>
        let totalSeconds = $timerDuration;
        let remainingSeconds = totalSeconds;
        let timerInterval = null;
        let isRunning = false;
        
        const timerDisplay = document.getElementById('timer');
        const startBtn = document.getElementById('startBtn');
        const pauseBtn = document.getElementById('pauseBtn');
        const resetBtn = document.getElementById('resetBtn');
        
        function updateDisplay() {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            timerDisplay.textContent = 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
            
            if (remainingSeconds <= 10 && remainingSeconds > 0) {
                timerDisplay.style.color = '#ff4444';
            } else if (remainingSeconds === 0) {
                timerDisplay.style.color = '#ff0000';
                timerDisplay.textContent = 'TIME UP!';
                stopTimer();
                playAlert();
            } else {
                timerDisplay.style.color = '#667eea';
            }
        }
        
        function startTimer() {
            if (isRunning) return;
            isRunning = true;
            timerInterval = setInterval(() => {
                if (remainingSeconds > 0) {
                    remainingSeconds--;
                    updateDisplay();
                } else {
                    stopTimer();
                }
            }, 1000);
        }
        
        function stopTimer() {
            isRunning = false;
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
        }
        
        function resetTimer() {
            stopTimer();
            remainingSeconds = totalSeconds;
            updateDisplay();
        }
        
        function playAlert() {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 800;
            oscillator.type = 'sine';
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        }
        
        startBtn.addEventListener('click', startTimer);
        pauseBtn.addEventListener('click', stopTimer);
        resetBtn.addEventListener('click', resetTimer);
        
        updateDisplay();
    </script>
</body>
</html>
HTML;
}

function generateWordClickSlideshowHTML($title, $timerDuration, $exercises) {
    $instructionsHtml = '';
    foreach ($exercises as $index => $exercise) {
        $num = $index + 1;
        $sentence = htmlspecialchars($exercise['sentence']);
        
        $verbsHtml = '';
        foreach ($exercise['verbs'] as $verb) {
            $text = htmlspecialchars($verb['text']);
            $options = implode(', ', array_map('htmlspecialchars', $verb['options']));
            $correct = htmlspecialchars($verb['correct']);
            $verbsHtml .= "<div><strong>Verb:</strong> $text → <strong>Options:</strong> $options → <strong>Correct:</strong> <span style='color: #4CAF50;'>$correct</span></div>";
        }
        
        $instructionsHtml .= <<<HTML
        <div class="exercise-section">
            <h3>Exercise $num</h3>
            <p class="exercise-sentence">$sentence</p>
            <div class="verb-details">
                $verbsHtml
            </div>
        </div>
HTML;
    }
    
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title - Teacher Guide</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #1a1a1a;
            color: white;
            padding: 2rem;
            line-height: 1.8;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
            color: #667eea;
        }
        .timer-section {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 3rem;
        }
        .timer-display {
            font-size: 4rem;
            font-weight: bold;
            color: #667eea;
            margin: 1rem 0;
            font-family: 'Courier New', monospace;
        }
        .timer-controls {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1rem;
        }
        .timer-btn {
            padding: 1rem 2rem;
            font-size: 1.2rem;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .timer-btn:hover {
            background: #5a67d8;
        }
        .timer-btn.reset {
            background: #444;
        }
        .timer-btn.reset:hover {
            background: #666;
        }
        .instructions {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
            color: #aaa;
        }
        .exercise-section {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border-left: 4px solid #667eea;
        }
        .exercise-section h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        .exercise-sentence {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            font-style: italic;
            color: #ddd;
        }
        .verb-details {
            font-size: 1.2rem;
            line-height: 2;
        }
        .verb-details div {
            margin-bottom: 0.5rem;
        }
        .verb-details strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>$title</h1>
        
        <div class="timer-section">
            <h2>Activity Timer</h2>
            <div class="timer-display" id="timer">00:00</div>
            <div class="timer-controls">
                <button class="timer-btn" id="startBtn">Start</button>
                <button class="timer-btn" id="pauseBtn">Pause</button>
                <button class="timer-btn reset" id="resetBtn">Reset</button>
            </div>
        </div>
        
        <div class="instructions">
            Students will click on verbs to cycle through options and select the correct form:
        </div>
        
        $instructionsHtml
    </div>
    
    <script>
        let totalSeconds = $timerDuration;
        let remainingSeconds = totalSeconds;
        let timerInterval = null;
        let isRunning = false;
        
        const timerDisplay = document.getElementById('timer');
        const startBtn = document.getElementById('startBtn');
        const pauseBtn = document.getElementById('pauseBtn');
        const resetBtn = document.getElementById('resetBtn');
        
        function updateDisplay() {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            timerDisplay.textContent = 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
            
            if (remainingSeconds <= 10 && remainingSeconds > 0) {
                timerDisplay.style.color = '#ff4444';
            } else if (remainingSeconds === 0) {
                timerDisplay.style.color = '#ff0000';
                timerDisplay.textContent = 'TIME UP!';
                stopTimer();
                playAlert();
            } else {
                timerDisplay.style.color = '#667eea';
            }
        }
        
        function startTimer() {
            if (isRunning) return;
            isRunning = true;
            timerInterval = setInterval(() => {
                if (remainingSeconds > 0) {
                    remainingSeconds--;
                    updateDisplay();
                } else {
                    stopTimer();
                }
            }, 1000);
        }
        
        function stopTimer() {
            isRunning = false;
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
        }
        
        function resetTimer() {
            stopTimer();
            remainingSeconds = totalSeconds;
            updateDisplay();
        }
        
        function playAlert() {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 800;
            oscillator.type = 'sine';
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        }
        
        startBtn.addEventListener('click', startTimer);
        pauseBtn.addEventListener('click', stopTimer);
        resetBtn.addEventListener('click', resetTimer);
        
        updateDisplay();
    </script>
</body>
</html>
HTML;
}

function generateManifest($title, $type) {
    $identifier = strtolower(str_replace(' ', '_', $title));
    
    return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<manifest xmlns="http://www.imsproject.org/xsd/imscp_rootv1p1p2" 
          xmlns:adlcp="http://www.adlnet.org/xsd/adlcp_rootv1p2" 
          identifier="$identifier" 
          version="1.2">
    <organizations default="org1">
        <organization identifier="org1">
            <title>$title</title>
            <item identifier="item1" identifierref="resource1">
                <title>$title</title>
            </item>
        </organization>
    </organizations>
    <resources>
        <resource identifier="resource1" type="webcontent" adlcp:scormtype="sco" href="index.html">
            <file href="index.html"/>
            <file href="SCORMGeneric.js"/>
        </resource>
    </resources>
</manifest>
XML;
}

function createZip($source, $destination) {
    if (!extension_loaded('zip')) {
        throw new Exception('ZIP extension not available');
    }
    
    $zip = new ZipArchive();
    if ($zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new Exception('Cannot create ZIP file');
    }
    
    $source = realpath($source);
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($files as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($source) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }
    
    $zip->close();
}
?>