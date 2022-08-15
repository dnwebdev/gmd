<form id="contactForm" name="sentMessage" action="" class="mb-5" novalidate="novalidate" novalidate>
    {{ csrf_field() }}
    <div class="row">
        <div class="col">
            <div class="form-group">
                <input class="form-control" id="name" name="name" type="text" placeholder="Your Name *" required>
            </div>
            <div class="form-group">
                <input class="form-control" id="email" name="email" type="email" placeholder="Your Email *" required>
            </div>
            <div class="form-group">
                <input class="form-control" id="phone" name="phone" type="tel" placeholder="Your Phone *" required>
            </div>
            <div class="form-group">
                <input class="form-control" id="message" name="message" placeholder="Your Message *" required>
            </div>
        </div>
        <div class="clearfix">
        </div>
        <div class="col-lg-12 text-center">            
            <div class="g-recaptcha" data-sitekey="6LfH9nMUAAAAAOqjGNs9LVJqIg1nac-tKFKVWOPs"></div>
            <p id="captcha-error" class="error help-block text-danger"></p>
            <button type="submit" class="btn btn-primary" name="submit" value="Submit">Submit</button>
        </div>
    </div>
</form>
