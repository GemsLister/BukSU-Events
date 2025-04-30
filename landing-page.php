<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukSU Events</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css-style/land-page.css">
</head>
<body>
    <header>
        <!-- Upper -->
        <div class="upper d-flex align-items-center">
            <figure class="d-flex justify-content-between align-items-center w-100 p-3">
                <img src="../images/buksu_events_logo.png" alt="web-logo">
                <div class="menu d-flex align-items-center d-sm-none">
                    <a href=""><i class="fas fa-bars"></i></a>
                </div>
            </figure>
            <nav class="d-flex align-items-sm-center gap-2 align-items-center align-items-sm-start justify-content-sm-end w-100 ">
                <ul class="d-none gap-3 d-sm-flex p-0">
                    <li><a href="">Home</a></li>
                    <li><a href="">Events</a></li>
                    <li><a href="">Colleges</a></li>
                    <li><a href="">About Us</a></li>
                </ul>
                <button class="btn btn-outline-light d-none d-sm-flex">Login</button>
            </nav>
        </div>
            
        <section class="lower-header w-100 d-flex flex-column align-items-center pb-4
        d-sm-flex flex-sm-row">
            <!-- Carousel -->
            <div id="web-carousel" class="w-carousel carousel slide d-none d-sm-flex" data-bs-ride="carousel">
                <div class="carousel-inner c-inner d-sm-flex justify-content-sm-flex-start">
                    <div class="carousel-item c-item active">
                        <img src="../images/connect.png" alt="booking image">
                    </div>
                    <div class="carousel-item c-item">
                        <img src="../images/enjoy.png" class="c-img" alt="...">
                    </div>
                    <div class="carousel-item c-item">
                        <img src="../images/innovate.png" class="c-img" alt="...">
                    </div>
                </div>
            </div>
            <!-- Tagline -->
             <div class="tagline-container d-flex flex-column align-items-center align-items-sm-start w-100 h-50 px-sm-2
             text-center text-sm-start">
                <div class="tagline text-sm-start">
                    <h1>Connect, Enjoy, Innovate</h1>
                </div>
                <div class="tagline-paragraph d-flex justify-content-center w-100">
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quaerat qui vel sit fuga sunt, consectetur at?
                    </p>
                </div>
             </div>
            <!-- Carousel -->
            <div id="web-carousel" class="w-carousel d-sm-none carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner c-inner">
                    <div class="carousel-item c-item active">
                        <img src="../images/connect.png" alt="booking image">
                    </div>
                    <div class="carousel-item c-item">
                        <img src="../images/enjoy.png" class="c-img" alt="...">
                    </div>
                    <div class="carousel-item c-item">
                        <img src="../images/innovate.png" class="c-img" alt="...">
                    </div>
                </div>
            </div>
        </section>

        <div class="inputs-container pb-5 d-none">
            <div class="inputs d-none d-sm-flex align-items-center px-3 py-4 gap-3">
                <!-- Selecting date -->
                <div class="select-date input-group">
                    <input type="date" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                </div>
                <!-- Selecting sites -->
                <div class="select-university-site dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    University Site
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="#">Auditorium</a></li>
                    <li><a class="dropdown-item" href="#">Gymnasium</a></li>
                    <li><a class="dropdown-item" href="#">Library</a></li>
                    <li><a class="dropdown-item" href="#">University Oval</a></li>
                    </ul>
                </div>
                <!-- Selecting event -->
                <div class="select-event dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        University Event
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div>
            </div>
            
        </div>
    </header>

    <main>
        <!-- Upcoming Events Row -->
        <div class="d-flex flex-column align-items-center">
            <div class="current-event d-flex justify-content-center my-5 row h-25">
                <div class="current-event-title d-flex justify-content-center">
                    <h1>Current Event</h1>
                </div>
                <div class="card current-event-card h-25">
                    <img src="../images/wide_orientation.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div class="event-name-text">
                            <h6>JAN <span>12</span> - University-Wide Orientation</h6>
                            <p class="card-text">This week is packed with excitement, designed just for you! Discover everything Bukidnon State University has to offer.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="upcoming-events d-flex justify-content-center gap-3 row px-0 px-sm-3">
                <div class="upcoming-events-title d-flex justify-content-center">
                    <h1>Upcoming Events</h1>
                </div>

                <!-- Shows if screen size is greater than mobile screen -->
                <div class="cards-container d-none d-sm-grid d-lg-grid">
                    <!-- First card -->
                    <div class="card f-card1">
                        <img src="../images/second_gen_assembly.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="event-name-text">
                                <h6>JAN <span>16</span> - Level Up: Together Toward Greater Challenges and Victories</h6>
                                <p class="card-text">An event that promises to inspire, challenge, and unite us as we embark on a journey toward achievements.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Second card -->
                    <div class="card f-card2">
                        <img src="../images/exciting_raffle_promo.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="event-name-text">
                                <h6>JAN <span>20</span> - Exciting Raffle Promo Awaits!!</h6>
                                <p class="card-text">Prepare to connect, inspire, and lead as ONE Smart BukSU. Let's continue to make history together!</p>
                            </div>
                        </div>
                    </div>
                    <!-- Third card -->
                    <div class="card f-card3">
                        <img src="../images/buksu_one.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="event-name-text">
                                <h6>JAN <span>20</span> - The Stage is Set: BukSUONE is Here!</h6>
                                <p class="card-text">Prepare to connect, inspire, and lead as ONE Smart BukSU. Let's continue to make history together!</p>
                            </div>
                        </div>
                    </div>
                    <!-- Fourth card -->
                    <div class="card f-card4 d-lg-flex">
                        <img src="../images/buksu_intrams.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="event-name-text">
                                <h6>MAR <span>10</span> - BUKSU INTRAMURALS</h6>
                                <p class="card-text">Speed. Strength. Spirit. Who will rise as this year's champion? Witness electrifying battles, awe-inspiring performances, and relentless action as Bukidnon State University sets the stage for its most intense competition yet!</p>
                            </div>
                        </div>
                    </div>
                    <!-- Fifth card -->
                    <div class="card f-card5 d-none d-lg-flex d-sm-none">
                        <img src="../images/buksu_intrams_parade.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="event-name-text">
                                <h6>MAR <span>10</span> - BUKSU INTRAMURALS</h6>
                                <p class="card-text">Speed. Strength. Spirit. Who will rise as this year's champion? Witness electrifying battles, awe-inspiring performances, and relentless action as Bukidnon State University sets the stage for its most intense competition yet!</p>
                            </div>
                        </div>
                    </div>
                    <!-- Sixth card -->
                    <div class="card f-card6 d-none d-lg-flex d-sm-none">
                        <img src="../images/closing_intrams.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="event-name-text">
                                <h6>MAR <span>10</span> - BUKSU INTRAMURALS</h6>
                                <p class="card-text">Speed. Strength. Spirit. Who will rise as this year's champion? Witness electrifying battles, awe-inspiring performances, and relentless action as Bukidnon State University sets the stage for its most intense competition yet!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- for mobile screens -->
                <div class="cards-container-mobile d-flex flex-column align-items-center gap-4 d-sm-none">
                    <!-- First card -->
                    <div class="card">
                        <img src="../images/second_gen_assembly.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="event-name-text">
                                <h6>JAN <span>16</span> - Level Up: Together Toward Greater Challenges and Victories</h6>
                                <p class="card-text">An event that promises to inspire, challenge, and unite us as we embark on a journey toward achievements.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Second card -->
                    <div class="card">
                        <img src="../images/exciting_raffle_promo.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="event-name-text">
                                <h6>JAN <span>20</span> - Exciting Raffle Promo Awaits!!</h6>
                                <p class="card-text">Prepare to connect, inspire, and lead as ONE Smart BukSU. Let's continue to make history together!</p>
                            </div>
                        </div>
                    </div>
                    <!-- Third card -->
                    <div class="card">
                        <img src="../images/buksu_intrams.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="event-name-text">
                                <h6>MAR <span>10</span> - BUKSU INTRAMURALS</h6>
                                <p class="card-text">Who will rise as this year's champion? Witness electrifying battles, awe-inspiring performances, and relentless action as Bukidnon State University sets the stage for its most intense competition yet!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-primary p-3 my-5 sm-col w-50 w-sm-25 w-lg-20">Load more</button>
            </div>
        </div>

        <!-- Colleges Row -->
         <div class="row">
            <div class="colleges d-flex flex-column align-items-center gap-4">
                <div class="colleges-title-text d-flex flex-column align-items-center">
                    <h1>Colleges</h1>
                    <p class="text-center">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Libero voluptates ut laudantium quaerat enim facilis quod.</p>
                </div>
            </div>
            <div class="department d-flex flex-column align-items-center flex-sm-row justify-content-sm-center">
                <div class="upper">
                    <figure class="d-flex gap-3">
                        <img src="../images/cas_college.png" alt="cas">
                        <img src="../images/cob_college.png" alt="cob">
                        <img src="../images/coe_college.png" alt="coe">
                        <img src="../images/law_college.png" alt="law">
                    </figure>
                </div>
                <div class="lower d-flex gap-3">
                    <figure>
                        <img src="../images/con_college.png" alt="con">
                        <img src="../images/cpag_college.png" alt="cpag">
                        <img src="../images/cot_college.png" alt="cot">
                    </figure>
                </div>
            </div>
         </div>
    </main>

    <footer class="p-3">
        <div class="copyrights">
            <figure class="d-flex flex-column align-items-center">
                <div class="web-logo mb-3">
                    <img src="../images/buksu_events_logo.png" alt="web-logo">
                </div>
                <div class="web-icons d-flex gap-3">
                    <a href=""><i class="fa-brands fa-facebook"></i></a>
                    <a href=""><i class="fa-brands fa-instagram"></i></a>
                    <a href=""><i class="fa-brands fa-twitter"></i></a>
                </div>
                <hr>
                <figcaption>Copyright &copy BukSU Events. All rights reserved</figcaption>
            </figure>
        </div>
    </footer>
    <script src="../bootstrap/js/bootstrap.bundle.js"></script>
</body>
</html>