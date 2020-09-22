<section id="contact-us">
    <div class="container pb-5">
        <div class="row">
            <div class="col-sm-12 pt-5 pb-4">
                <h2 class="title text-center text-white">Contact Us</h2>
            </div>
        </div>
        <form id="contact-form" action="{{url('contact/send-mail')}}" method="post">
            {{csrf_field()}}
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="name" placeholder="Name">
                        <span class="text-danger d-none">Name is required!</span>
                    </div>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="from_email" placeholder="Email">
                        <span class="text-danger d-none">Email is required!</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <input class="form-control" type="text" name="subject" placeholder="Subject">
                        <span class="text-danger d-none">Subject is required!</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <textarea rows="5" class="form-control" name="message" placeholder="Message"></textarea>
                        <span class="text-danger d-none">Message is required!</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6 text-right">
                        <input type="button" onClick="save()" value="Submit" class="btn btn-primary pl-5 pr-5 pt-2 pb-2">
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<script src="{{asset('js/contact-us.js?srfs45f')}}"></script>
