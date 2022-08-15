@extends('public.memoria.base_layout')

@section('title', 'Home')



@include('public.memoria.header')

@section('content')
<!-- Background -->
<section id="background">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 order-lg-2">
          <div class="p-5">
            <img class="img-fluid" src="landing-page/assets/images/about-1.png" alt="">
          </div>
        </div>
        <div class="col-lg-6 order-lg-1">
          <div class="p-5">
            <h4>Background</h4>
            <p>If you are a tour provider or an activities provider in the tourism industry, it can be hard to establish a strong
              online presence and accept bookings online. There are countless information technology terms that can be difficult
              to understand, such as domain, sub-domain, CMS, CRM, DNS servers, and many more.
            </p>
            <p class="font-weight-bold">You might not know how to code in one of many programming languages such as php, Go, JavaScript, Python, just
              to name a few.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- How does it works? -->
  <section id="how-it-works">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="p-5">
            <img class="img-fluid" src="landing-page/assets/images/about-2.png" alt="">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="p-5">
            <h4>How does it work?</h4>
            <p>
              <span class="font-weight-bold">Gomodo completely removes all of these barriers</span>, and allows you get a free website and start selling
              tours and activities online in a few steps, within 15 minutes, all from your mobile phone!</p>
            <button type="button" class="btn btn-primary">Register</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Benefits -->
  <section id="benefit" class="bg-white text-center">
    <div class="container">
      <h4 class="font-weight-bold pt-5">Benefits of using Gomodo</h4>
      <div class="row">
        <div class="col-lg-4">
          <div class="p-5">
            <div class="d-flex justify-content-center">
              <img class="img-fluid" src="landing-page/assets/images/benefit-1.png" alt="">
            </div>
            <h4>Claim your free website</h4>
            <p class="font-weight-light">Get discovered online by quickly creating a free, beautiful website: [YourBusiness].{{env('APP_URL')}}</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="p-5">
            <div class="d-flex justify-content-center">
              <img class="img-fluid" src="landing-page/assets/images/benefit-2.png" alt="">
            </div>
            <h4>Create tours and activities to sell in your booking section</h4>
            <p class="font-weight-light">Quickly create items to display on your new website</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="p-5">
            <div class="d-flex justify-content-center">
              <img src="landing-page/assets/images/benefit-3.png" alt="">
            </div>
            <h4>Immediately receive online bookings and payments!</h4>
            <p class="font-weight-light">Customers are able to pay for your tours and activities online with various payment options, or if you prefer,
              let them pay you with cash onsite
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Tools -->
  <section id="tools" class="bg-white text-left">
    <div class="container">
      <div class="p-5 text-center">
        <span class="d-inline-block text-wrap">
          Gomodo offers the following tools so you can enhance your online capabilities and generate more business, all for
          <span class="font-weight-bold">FREE</span>:
        </span>
      </div>
      <!-- Row 1 Tools -->
      <div class="row">
        <!-- Invoicing -->
        <div class="col-lg-4">
          <div class="p-5">
            <div class="row">
              <div class="col-4">
                <img src="landing-page/assets/images/tools-1.png" alt="">
              </div>
              <div class="col-8">
                <p class="font-weight-bold mb-0">Convenient Invoicing System</p>
                <p class="font-weight-light">Generate and send invoices via email and/or SMS (coming soon!) to your offline customers and invite them
                  to pay online with various payment options</p>
              </div>
            </div>
          </div>
        </div>
        <!-- Calendar -->
        <div class="col-lg-4">
          <div class="p-5">
            <div class="row">
              <div class="col-4">
                <img src="landing-page/assets/images/tools-2.png" alt="">
              </div>
              <div class="col-8">
                <p class="font-weight-bold mb-0">Manage your bookings in your Calendar</p>
                <p class="font-weight-light">Track orders and payments and update the statuses of your bookings</p>
              </div>
            </div>
          </div>
        </div>
        <!-- Database -->
        <div class="col-lg-4">
          <div class="p-5">
            <div class="row">
              <div class="col-4">
                <img src="landing-page/assets/images/tools-3.png" alt="">
              </div>
              <div class="col-8">
                <p class="font-weight-bold mb-0">Build Up Your Customer Database</p>
                <p class="font-weight-light">Store customer contact details as well as their purchase history</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Row 2 Tools -->
      <div class="row">
        <!-- Performance -->
        <div class="col-lg-4">
          <div class="p-5">
            <div class="row">
              <div class="col-4">
                <img src="landing-page/assets/images/tools-4.png" alt="">
              </div>
              <div class="col-8">
                <p class="font-weight-bold mb-0">Review Your Performance</p>
                <p class="font-weight-light">View and download sales reports for accounting</p>
              </div>
            </div>
          </div>
        </div>
        <!-- Voucher -->
        <div class="col-lg-4">
          <div class="p-5">
            <div class="row">
              <div class="col-4">
                <img src="landing-page/assets/images/tools-5.png" alt="">
              </div>
              <div class="col-8">
                <p class="font-weight-bold mb-0">Generate Redeemable Voucher Codes</p>
                <p class="font-weight-light">Reward your most loyal customers to encourage repeat business</p>
              </div>
            </div>
          </div>
        </div>
        <!-- Distribution -->
        <div class="col-lg-4">
          <div class="p-5">
            <div class="row">
              <div class="col-4">
                <img src="landing-page/assets/images/tools-6.png" alt="">
              </div>
              <div class="col-8">
                <p class="font-weight-bold mb-0">Distribution System (coming soon!)</p>
                <p class="font-weight-light">Distribute your tours and activities to a reseller network that includes major international online travel
                  agents
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="text-center p-5">
        <h4 class="font-weight-bold">More to Come!</h4>
      </div>
    </div>
  </section>

  <!-- Contact -->
  <section id="contact">
    <div class="container">
      <div class="row p-5">
        <div class="col-lg-12 text-center">
          <h2 class="section-heading">Contact Us</h2>
          <p class="section-subheading text-white">We are currently preparing Gomodo to be ready for public beta testing. If you are interested in updates and/or
            helping us test our software platform and/or if you have any suggestions or feedback and/or you would like to
            join our team, do reach out to us by filling in the form below. We promise we won’t spam you!</p>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
        @include('public.memoria.contact_form')
        </div>
      </div>
    </div>
  </section>
@endsection
