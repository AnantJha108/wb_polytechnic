@extends('frontend.template1.layout.app')

@section('title', 'Contact || ' . $college->name)

@section('content')
<div class="container px-5 mt-5 mb-5">
    <section>
        <div class="d-flex mb-4">
            <h2>Contact || </h2>
            <h2>{{ $college->name }}</h2>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card p-4 h-100">
                    <h4 class="mb-4">Get in Touch</h4>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-geo-alt-fill fs-4 me-3 text-primary"></i>
                        <div>
                            <strong>Address</strong>
                            <p class="mb-0">
                                {{ $college->address ?? 'Address not available' }}
                                @if ($college->district)
                                    <br>{{ $college->district }}
                                @endif
                                <br>West Bengal
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-telephone-fill fs-4 me-3 text-primary"></i>
                        <div>
                            <strong>Phone</strong>
                            <p class="mb-0">
                                @if ($college->contact_no)
                                    <a class="text-decoration-none text-dark" href="tel:{{ $college->contact_no }}">{{ $college->contact_no }}</a>
                                @else
                                    Not available
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-envelope-fill fs-4 me-3 text-primary"></i>
                        <div>
                            <strong>Email</strong>
                            <p class="mb-0">
                                @if ($college->email)
                                    <a class="text-decoration-none text-dark" href="mailto:{{ $college->email }}">{{ $college->email }}</a>
                                @else
                                    Not available
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection