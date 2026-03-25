<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Government Polytechnic Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f3f3f3;
        }

        /* Notice */

        .notice-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }

        .notice-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        /* College Cards */

        .college-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: 0.3s;
        }

        .college-card:hover {
            transform: translateY(-5px);
        }

        .college-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        /* Footer */

        .footer {
            background: #0f6e8c;
            color: white;
            padding: 40px 0;
        }

        .footer a {
            color: white;
            text-decoration: none;
        }

        .bottom-footer {
            background: #084e63;
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>

</head>

<body>

    <div class="container-fluid">
        <img src="https://polytechnic.wbtetsd.gov.in/themes/main/images/banner_landing.jpg" width="100%" alt="">
    </div>

    <div class="container">

        <!-- NOTICE -->

        <div class="notice-box">

            <h5>Latest Notices</h5>

            <div class="notice-item d-flex justify-content-between">

                <span>
                    1st waiting list of Diploma passout selected candidates
                </span>

                <button class="btn btn-sm btn-primary">Download PDF</button>

            </div>

            <div class="notice-item d-flex justify-content-between">

                <span>
                    1st waiting list of ITI passout selected candidates
                </span>

                <button class="btn btn-sm btn-primary">Download PDF</button>

            </div>

        </div>


        <!-- POLYTECHNIC LIST -->

        <div class="mt-5">

            <div class="d-flex justify-content-between mb-3">

                <h3 class="text-success">Government Polytechnics</h3>

                <input type="text" class="form-control w-25" placeholder="College search">

            </div>

            <div class="row g-4">
                @foreach($colleges as $college)
                <div class="col-md-4">
                    <a href="{{ url($college->slug) }}" class="text-decoration-none text-dark" target="_blank">
                        <div class="college-card">
                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png" class="college-logo">
                            <span>{{ $college->name }}</span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-4">

                <button class="btn btn-outline-success px-5">View More</button>

            </div>

        </div>

    </div>


    <!-- FOOTER -->

    <div class="footer mt-5">

        <div class="container">

            <div class="row">

                <div class="col-md-4">

                    <h5>Useful Links</h5>

                    <p><a href="#">AICTE Website</a></p>
                    <p><a href="#">Council Website</a></p>

                </div>

                <div class="col-md-4">

                    <h5>Contact Us</h5>

                    <p>
                        Directorate of Technical Education<br>
                        Karigari Bhawan<br>
                        Newtown, Rajarhat<br>
                        Kolkata 700160
                    </p>

                </div>

                <div class="col-md-4">

                    <h5>Location</h5>

                    <iframe src="https://maps.google.com/maps?q=karigari%20bhawan&t=&z=13&ie=UTF8&iwloc=&output=embed"
                        width="100%" height="150" style="border:0;">
                    </iframe>

                </div>

            </div>

        </div>

    </div>

    <div class="bottom-footer">

        © Department of Technical Education, Government of West Bengal

    </div>

</body>

</html>