<div class="reports-manage">

    <!-- SIDEBAR -->
    <?= view('include/sidebar', ['active' => 'reports']) ?>

    <!-- MAIN CONTENT -->
    <main class="reports-main">
        <div class="reports-card">

            <!-- HEADER -->
            <div class="mb-4 text-center">
                <h1 class="reports-title">Reports Dashboard</h1>
                <p class="reports-sub">View active equipment, unusable equipment, and user borrowing history.</p>
            </div>

            <!-- REPORT CARDS STACK -->
            <div class="reports-stack">

                <!-- Active Equipment Card -->
                <div class="report-subcard">
                    <h2 class="report-subcard-title">Active Equipment</h2>
                    <ul class="report-list">
                        <li>
                            <span>Laptop (with charger)</span>
                            <span class="status-badge active">Active</span>
                        </li>
                        <li>
                            <span>HDMI Cable</span>
                            <span class="status-badge active">Active</span>
                        </li>
                        <li>
                            <span>Keyboard & Mouse</span>
                            <span class="status-badge active">Active</span>
                        </li>
                        <li>
                            <span>Webcam</span>
                            <span class="status-badge active">Active</span>
                        </li>
                    </ul>
                </div>

                <!-- Unusable Equipment Card -->
                <div class="report-subcard">
                    <h2 class="report-subcard-title">Unusable Equipment</h2>
                    <ul class="report-list">
                        <li>
                            <span>DLP Projector</span>
                            <span class="status-badge unusable">Broken lamp</span>
                        </li>
                        <li>
                            <span>Wacom Drawing Tablet</span>
                            <span class="status-badge unusable">Pen missing</span>
                        </li>
                        <li>
                            <span>Speaker Set</span>
                            <span class="status-badge unusable">Malfunctioning</span>
                        </li>
                    </ul>
                </div>

                <!-- User Borrowing History Card -->
                <div class="report-subcard">
                    <h2 class="report-subcard-title">User Borrowing History</h2>
                    <ul class="report-list">
                        <li>
                            <span>Jane Doe - HDMI Cable</span>
                            <span class="status-badge borrowed">2025-11-15</span>
                        </li>
                        <li>
                            <span>John Smith - Keyboard & Mouse</span>
                            <span class="status-badge borrowed">2025-11-18</span>
                        </li>
                        <li>
                            <span>Mary Lee - Laptop</span>
                            <span class="status-badge borrowed">2025-11-20</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </main>
</div>