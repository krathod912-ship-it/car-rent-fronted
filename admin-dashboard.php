<?php
$pageTitle = 'Admin Dashboard';
include __DIR__ . '/partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-3">Admin Dashboard</h2>
        <p class="text-muted small mb-4">
            Frontend-only admin panel to display bookings, cars, ratings and a mock chat section.
        </p>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small mb-1">Total Bookings</h6>
                        <h3 class="fw-bold">128</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small mb-1">Cars Listed</h6>
                        <h3 class="fw-bold">24</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small mb-1">Avg. Rating</h6>
                        <h3 class="fw-bold">4.6 / 5</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Recent Bookings</h6>
                        <span class="badge bg-primary-subtle text-primary small">UI only</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Car</th>
                                        <th>Dates</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Riya Sharma</td>
                                        <td>Hyundai Creta</td>
                                        <td>28 Jan - 30 Jan</td>
                                        <td><span class="badge bg-success-subtle text-success">Confirmed</span></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Arjun Mehta</td>
                                        <td>Maruti Swift</td>
                                        <td>02 Feb - 03 Feb</td>
                                        <td><span class="badge bg-warning-subtle text-warning">Pending</span></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Neha Verma</td>
                                        <td>Tesla Model 3</td>
                                        <td>05 Feb - 07 Feb</td>
                                        <td><span class="badge bg-danger-subtle text-danger">Cancelled</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0">Latest Reviews</h6>
                    </div>
                    <div class="card-body small">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>Rahul</strong>
                                <span class="text-warning">★★★★☆</span>
                            </div>
                            <p class="mb-1">Great experience, car was clean and pickup was on time.</p>
                            <span class="text-muted">Car: Hyundai Creta</span>
                        </div>
                        <hr />
                        <div class="mb-0">
                            <div class="d-flex justify-content-between">
                                <strong>Ananya</strong>
                                <span class="text-warning">★★★★★</span>
                            </div>
                            <p class="mb-1">Loved the Tesla! Smooth and silent ride.</p>
                            <span class="text-muted">Car: Tesla Model 3</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Support Chat (Admin View)</h6>
                        <span class="badge bg-info-subtle text-info small">Prototype</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="chat-window small mb-3">
                            <div class="chat-bubble chat-bubble-user mb-2">
                                Hi, I want to modify my booking for tomorrow.
                            </div>
                            <div class="chat-bubble chat-bubble-admin mb-2">
                                Sure, please share your booking ID. I will check the availability.
                            </div>
                        </div>
                        <form class="chat-input-area mt-auto">
                            <div class="input-group input-group-sm">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Type a reply..."
                                    aria-label="Type a reply"
                                />
                                <button class="btn btn-primary" type="button">Send</button>
                            </div>
                            <p class="small text-muted mt-2 mb-0">
                                Messages are not saved in CIE‑1; this is a front-end UI demo only.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>

