<!-- footer -->
<div class="footer-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="footer-box about-widget" style="width: 100%;">
                    <h2 class="widget-title">About us</h2>
                    @php
                        $description = $data['about']['description'];
                        $wordLimit = 30;
                        $words = explode(' ', $description);
                        $shortDescription = implode(' ', array_slice($words, 0, $wordLimit));
                        $isLongText = count($words) > $wordLimit;
                    @endphp
                    <p id="description" style="text-align: justify;">
                        {{ $shortDescription }}@if ($isLongText)
                            ...
                            <a href="javascript:void(0)" onclick="toggleDescription()" id="seeMoreLink">See More</a>
                        @endif
                    </p>
                    <p id="fullDescription" style="display: none; text-align: justify;">
                        {{ $description }}
                        <a href="javascript:void(0)" onclick="toggleDescription()" id="seeLessLink">See Less</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-box get-in-touch">
                    <h2 class="widget-title">Get in Touch</h2>
                    <ul>
                        <li>{{ $data['contact']['address'] }}</li>
                        <li>Phone : {{ $data['contact']['phone_number'] }}</li>
                        <li>Whatsapp : {{ $data['contact']['whatsapp'] }}</li>
                        <li>Email : {{ $data['contact']['email'] }}</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-box pages">
                    <h2 class="widget-title">Pages</h2>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="{{ route('fo.about.index') }}">About</a></li>
                        <li><a href="{{ route('shop-product.index') }}">Shop</a></li>
                        <li><a href="{{ route('fo.event.index') }}">Event</a></li>
                        <li><a href="{{ route('fo.contact.index') }}">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-box subscribe">
                    <h2 class="widget-title">Subscribe</h2>
                    <form action="index.html">
                        <input type="email" placeholder="Email">
                        <button type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end footer -->

<!-- copyright -->
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <p>Copyrights &copy; 2024</p>
            </div>
            <div class="col-lg-6 text-right col-md-12">
                <div class="social-icons">
                    <ul>
                        <!-- <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-linkedin"></i></a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-dribbble"></i></a></li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end copyright -->

<a class="wafixed" href="https://wa.me/{{ $data['contact']['whatsapp'] }}?text=Hallo%20Astraea%20Leather%20Craft"
    target="_blank">
    <span class="fa-stack fa-lg">
        <img src="{{ asset('guest/img/wa.png') }}" class="wafixed-imag">
    </span>
</a>

<script>
    function toggleDescription() {
        var shortDesc = document.getElementById('description');
        var fullDesc = document.getElementById('fullDescription');
        var seeMoreLink = document.getElementById('seeMoreLink');
        var seeLessLink = document.getElementById('seeLessLink');

        if (fullDesc.style.display === 'none') {
            shortDesc.style.display = 'none';
            fullDesc.style.display = 'block';
            seeMoreLink.style.display = 'none';
        } else {
            shortDesc.style.display = 'block';
            fullDesc.style.display = 'none';
            seeMoreLink.style.display = 'block';
        }
    }
</script>
