@extends('layouts.app')
@section('title', 'TOEFL')

@section('content')
    <div class="toefl-page">
        <header class="toefl-welcome">
            <div class="toefl-welcome-copy">
                <p class="toefl-eyebrow"><span class="toefl-sun">✳</span> {{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
                <h1>TOEFL era, let's go.</h1>
                <p class="toefl-subtitle">Pelan-pelan aja, Grisel. You're getting there. <span aria-hidden="true">✦</span></p>
            </div>
            <div class="toefl-welcome-art" aria-hidden="true">
                <div class="toefl-sun-disc"></div>
                <div class="toefl-cloud toefl-cloud-one"></div>
                <div class="toefl-cloud toefl-cloud-two"></div>
                <div class="toefl-window-line"></div>
                <div class="toefl-book-stack"><span>TOEFL</span><i></i><i></i><i></i></div>
                <span class="toefl-star star-one">✦</span>
                <span class="toefl-star star-two">✧</span>
            </div>
        </header>

        <section class="toefl-hero" aria-labelledby="toefl-focus-title">
            <div class="toefl-hero-main">
                <div class="toefl-hero-top">
                    <span class="toefl-icon-bubble" aria-hidden="true">↗</span>
                    <span class="toefl-status">CURRENT FOCUS</span>
                </div>
                <p class="toefl-kicker">Your next chapter starts here</p>
                <h2 id="toefl-focus-title">TOEFL Preparation</h2>
                <p class="toefl-hero-description">Build your English, one practice at a time.</p>
                <div class="toefl-progress-meta">
                    <span>Study progress</span>
                    <strong id="toefl-progress-label">0% complete</strong>
                </div>
                <div class="toefl-progress-track" role="progressbar" aria-label="Progress tugas TOEFL" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="toefl-progress-bar">
                    <span id="toefl-progress-fill"></span>
                </div>
                <p class="toefl-progress-foot"><span id="toefl-done-count">0 of 4 tasks</span><span>Small steps count.</span></p>
            </div>
            <div class="toefl-hero-note">
                <span class="toefl-note-tape"></span>
                <p class="toefl-note-label">A little reminder</p>
                <p class="toefl-note-quote">You don't need to be perfect. You just need to begin.</p>
                <span class="toefl-note-doodle" aria-hidden="true">♡</span>
            </div>
            <span class="toefl-sparkle sparkle-a" aria-hidden="true">✦</span>
            <span class="toefl-sparkle sparkle-b" aria-hidden="true">✧</span>
        </section>

        <div class="toefl-content-grid">
            <section class="toefl-panel toefl-tasks-panel">
                <div class="toefl-panel-heading">
                    <div>
                        <p class="toefl-section-kicker">MAKE IT HAPPEN</p>
                        <h2>Study checklist <span aria-hidden="true">✎</span></h2>
                    </div>
                    <button type="button" class="toefl-add-button" id="toefl-add-toggle"><span aria-hidden="true">＋</span> Add task</button>
                </div>

                <form class="toefl-add-form" id="toefl-add-form" hidden>
                    <label for="toefl-new-task">What do you want to work on?</label>
                    <div class="toefl-add-row">
                        <input id="toefl-new-task" name="task" maxlength="100" placeholder="e.g. Practice listening for 20 minutes" required>
                        <button class="toefl-add-submit" type="submit">Add</button>
                    </div>
                    <p class="toefl-form-hint">Demo mode: new tasks are temporary and reset when you refresh.</p>
                </form>

                <div class="toefl-filter-row" role="group" aria-label="Filter study tasks">
                    <button type="button" class="toefl-filter is-active" data-filter="all">All tasks</button>
                    <button type="button" class="toefl-filter" data-filter="open">To do</button>
                    <button type="button" class="toefl-filter" data-filter="done">Completed</button>
                </div>

                <div class="toefl-task-list" id="toefl-task-list">
                    <label class="toefl-task" data-task-item>
                        <input type="checkbox" class="toefl-task-check">
                        <span class="toefl-check-ui" aria-hidden="true"></span>
                        <span class="toefl-task-copy"><strong>Listening practice</strong><small>Try one short lecture · 20 min</small></span>
                        <span class="toefl-task-tag tag-lavender">LISTENING</span>
                    </label>
                    <label class="toefl-task" data-task-item>
                        <input type="checkbox" class="toefl-task-check">
                        <span class="toefl-check-ui" aria-hidden="true"></span>
                        <span class="toefl-task-copy"><strong>Read one academic passage</strong><small>Focus on main idea & details</small></span>
                        <span class="toefl-task-tag tag-yellow">READING</span>
                    </label>
                    <label class="toefl-task" data-task-item>
                        <input type="checkbox" class="toefl-task-check">
                        <span class="toefl-check-ui" aria-hidden="true"></span>
                        <span class="toefl-task-copy"><strong>Review 15 vocabulary words</strong><small>Save tricky words for later</small></span>
                        <span class="toefl-task-tag tag-mint">VOCAB</span>
                    </label>
                    <label class="toefl-task" data-task-item>
                        <input type="checkbox" class="toefl-task-check">
                        <span class="toefl-check-ui" aria-hidden="true"></span>
                        <span class="toefl-task-copy"><strong>Speaking: record a 45-sec answer</strong><small>Practice first, polish later</small></span>
                        <span class="toefl-task-tag tag-pink">SPEAKING</span>
                    </label>
                </div>
                <div class="toefl-empty-state" id="toefl-empty-state" hidden>No tasks here for now. Enjoy the breathing room ♡</div>
                <p class="toefl-task-footer"><span aria-hidden="true">✦</span> Done is better than perfect.</p>
            </section>

            <aside class="toefl-side-stack">
                <section class="toefl-panel toefl-score-panel">
                    <div class="toefl-small-panel-heading">
                        <span class="toefl-mini-icon mini-lavender" aria-hidden="true">↗</span>
                        <div><p class="toefl-section-kicker">YOUR TARGET</p><h2>Score tracker</h2></div>
                    </div>
                    <div class="toefl-score-row">
                        <div class="toefl-score-circle"><span id="toefl-score-value">—</span><small>latest score</small></div>
                        <div class="toefl-score-details">
                            <label for="toefl-score-input">Latest tryout score</label>
                            <input id="toefl-score-input" type="number" min="0" max="120" placeholder="e.g. 450" inputmode="numeric">
                            <p class="toefl-score-help">TOEFL ITP: 310–677 · iBT: 0–120</p>
                            <button type="button" class="toefl-save-score" id="toefl-save-score">Update score</button>
                        </div>
                    </div>
                    <p class="toefl-demo-notice">Score preview only; it isn't saved to your account yet.</p>
                </section>

                <section class="toefl-panel toefl-skills-panel">
                    <div class="toefl-small-panel-heading">
                        <span class="toefl-mini-icon mini-mint" aria-hidden="true">✳</span>
                        <div><p class="toefl-section-kicker">THE FOUR SKILLS</p><h2>Your study mix</h2></div>
                    </div>
                    <div class="toefl-skill-row"><span class="toefl-skill-dot skill-listening"></span><span>Listening</span><span class="toefl-skill-hint">Tune in</span></div>
                    <div class="toefl-skill-row"><span class="toefl-skill-dot skill-reading"></span><span>Reading</span><span class="toefl-skill-hint">Find the clue</span></div>
                    <div class="toefl-skill-row"><span class="toefl-skill-dot skill-speaking"></span><span>Speaking</span><span class="toefl-skill-hint">Say it out loud</span></div>
                    <div class="toefl-skill-row"><span class="toefl-skill-dot skill-writing"></span><span>Writing</span><span class="toefl-skill-hint">Make your point</span></div>
                </section>

                <section class="toefl-motivation">
                    <div class="toefl-motivation-top"><span aria-hidden="true">☀</span><strong>Today's reminder</strong></div>
                    <p id="toefl-motivation-text">You are allowed to start small and still dream big.</p>
                    <button type="button" id="toefl-new-quote" class="toefl-quote-refresh">Another one ↻</button>
                    <span class="toefl-motivation-heart" aria-hidden="true">♡</span>
                </section>
            </aside>
        </div>

        <footer class="toefl-bottom-note">
            <span class="toefl-bottom-star" aria-hidden="true">✦</span>
            <p><strong>Remember, Grisel:</strong> this is your journey. No need to race anyone else.</p>
            <span class="toefl-bottom-signature">next chapter ↗</span>
        </footer>
    </div>

    <script>
        (() => {
            const root = document.querySelector('.toefl-page');
            if (!root) return;

            const taskList = root.querySelector('#toefl-task-list');
            const progressFill = root.querySelector('#toefl-progress-fill');
            const progressLabel = root.querySelector('#toefl-progress-label');
            const progressBar = root.querySelector('#toefl-progress-bar');
            const doneCount = root.querySelector('#toefl-done-count');
            const emptyState = root.querySelector('#toefl-empty-state');
            const filterButtons = [...root.querySelectorAll('[data-filter]')];
            let activeFilter = 'all';

            const updateProgress = () => {
                const tasks = [...taskList.querySelectorAll('[data-task-item]')];
                const completed = tasks.filter(task => task.querySelector('.toefl-task-check').checked).length;
                const percent = tasks.length ? Math.round((completed / tasks.length) * 100) : 0;
                progressFill.style.width = `${percent}%`;
                progressLabel.textContent = `${percent}% complete`;
                progressBar.setAttribute('aria-valuenow', percent);
                doneCount.textContent = `${completed} of ${tasks.length} tasks`;
                applyFilter();
            };

            const applyFilter = () => {
                const tasks = [...taskList.querySelectorAll('[data-task-item]')];
                let visible = 0;
                tasks.forEach(task => {
                    const done = task.querySelector('.toefl-task-check').checked;
                    const show = activeFilter === 'all' || (activeFilter === 'done' && done) || (activeFilter === 'open' && !done);
                    task.hidden = !show;
                    if (show) visible++;
                });
                emptyState.hidden = visible !== 0;
            };

            taskList.addEventListener('change', event => {
                if (event.target.matches('.toefl-task-check')) {
                    event.target.closest('[data-task-item]').classList.toggle('is-done', event.target.checked);
                    updateProgress();
                }
            });

            filterButtons.forEach(button => button.addEventListener('click', () => {
                activeFilter = button.dataset.filter;
                filterButtons.forEach(item => item.classList.toggle('is-active', item === button));
                applyFilter();
            }));

            const addToggle = root.querySelector('#toefl-add-toggle');
            const addForm = root.querySelector('#toefl-add-form');
            const newTaskInput = root.querySelector('#toefl-new-task');
            addToggle.addEventListener('click', () => {
                addForm.hidden = !addForm.hidden;
                if (!addForm.hidden) newTaskInput.focus();
                addToggle.innerHTML = addForm.hidden ? '<span aria-hidden="true">＋</span> Add task' : '<span aria-hidden="true">−</span> Cancel';
            });

            addForm.addEventListener('submit', event => {
                event.preventDefault();
                const value = newTaskInput.value.trim();
                if (!value) return;

                const label = document.createElement('label');
                label.className = 'toefl-task';
                label.dataset.taskItem = '';
                label.innerHTML = `
                    <input type="checkbox" class="toefl-task-check">
                    <span class="toefl-check-ui" aria-hidden="true"></span>
                    <span class="toefl-task-copy"><strong></strong><small>Added just now · Your pace, your plan</small></span>
                    <span class="toefl-task-tag tag-lavender">CUSTOM</span>`;
                label.querySelector('strong').textContent = value;
                taskList.appendChild(label);
                newTaskInput.value = '';
                addForm.hidden = true;
                addToggle.innerHTML = '<span aria-hidden="true">＋</span> Add task';
                activeFilter = 'all';
                filterButtons.forEach(item => item.classList.toggle('is-active', item.dataset.filter === 'all'));
                updateProgress();
            });

            const scoreInput = root.querySelector('#toefl-score-input');
            const scoreValue = root.querySelector('#toefl-score-value');
            root.querySelector('#toefl-save-score').addEventListener('click', () => {
                const score = Number(scoreInput.value);
                if (scoreInput.value.trim() === '' || !Number.isFinite(score) || score < 0 || score > 677) {
                    scoreInput.setCustomValidity('Masukkan skor angka yang valid (0–677).');
                    scoreInput.reportValidity();
                    return;
                }
                scoreInput.setCustomValidity('');
                scoreValue.textContent = String(score);
                scoreInput.value = '';
            });

            const quotes = [
                'You are allowed to start small and still dream big.',
                'Consistency beats one perfect study day.',
                'Future you will be glad you started today.',
                'A little progress is still progress. Keep going.',
                'You can take your time without giving up.'
            ];
            let quoteIndex = 0;
            root.querySelector('#toefl-new-quote').addEventListener('click', () => {
                quoteIndex = (quoteIndex + 1) % quotes.length;
                root.querySelector('#toefl-motivation-text').textContent = quotes[quoteIndex];
            });

            updateProgress();
        })();
    </script>
@endsection
