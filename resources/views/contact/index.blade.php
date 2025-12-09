@extends('layouts.home.template')
@section('title', 'Contact Us')
@section('content')
    <!-- page title start -->
    <div class="breadcrumb-area bg-cover" style="background-image: url('{{ asset('assets/img/bg/4.png') }}');">
        <div class="container">
            <div class="breadcrumb-inner">
                <h2 class="page-title">Contact <span>Us</span></h2>
                <ul class="page-list">
                    <li><a href="index.html">Home</a></li>
                    <li>Contact Us</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- page title end -->

    <!-- Contact Us Section Start -->
    <section class="contact-us-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3 class="section-title mb-4">Kontak Kami</h3>
                    <p>Jika Anda memiliki pertanyaan atau masukan, jangan ragu untuk menghubungi kami. Kami selalu siap
                        membantu Anda. Anda bisa menghubungi kami melalui form di bawah ini atau menggunakan informasi
                        kontak yang tertera.</p>
                    <ul class="list-unstyled">
                        <li><strong>Alamat:</strong> Jl. Contoh Alamat No. 123, Kota X, Indonesia</li>
                        <li><strong>Email:</strong> support@ketikarab.com</li>
                        <li><strong>Telepon:</strong> +62 123 456 789</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <h3 class="section-title mb-4">Form Kontak</h3>
                    <form action="mailto:support@ketikarab.com?subject=Pesan dari Website" method="POST"
                        enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Anda</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Anda</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Us Section End -->
@endsection
