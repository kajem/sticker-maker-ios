@extends('layouts.front-template')
@section('content')
    <div class="content mb-5" id="about-us">
        <div class="container">
            <h2 class="title pt-5">About us</h2>
            <hr/>

            <div class="main-content">
                <div class="about-text pb-5">
                    Sticker Maker is a product of Brain Craft Limited which is a software development company currently specialising
                in Mobile applications development in the iOS App Store and the Google Play Store, the two primary areas are currently
                under active development with more than 28 free downloadable and subscription-based mobile applications. The company
                has an enviable number of top charted apps in the iOS App Store.
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-3 col-lg-2 offset-lg-1">
                                <img class="icon" src="{{asset('images/about-us/exclusive-design.png')}}" alt="Exclusive design" />
                            </div>
                            <div class="col-sm-8">
                                <h5 class="font-weight-bold">Exclusive design</h5>
                                <div class="text">
                                    Apps are peerlessly designed and highly polished as artistic and enthusiast designs are utmost
                                    required from our designers which brought to us much appreciation and success
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-3 col-lg-2 offset-lg-1">
                                <img class="icon last" src="{{asset('images/about-us/high-quality-copy.png')}}" alt="High Quality" />
                            </div>
                            <div class="col-sm-8">
                                <h5 class="font-weight-bold">High Quality</h5>
                                <div class="text">
                                    We develop posh apps by maintaining the utmost degree of client satisfaction, their interest and desires at the forefront of
                                    all our thoughts, and devote the energy, resources and commitment required
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-3 col-lg-2 offset-lg-1">
                                <img class="icon" src="{{asset('images/about-us/fully-secured.png')}}" alt="Fully Secured" />
                            </div>
                            <div class="col-sm-8">
                                <h5 class="font-weight-bold">Fully Secured</h5>
                                <div class="text">
                                    We shield customer's confidentiality and ensure data protection. We do not disclose our customers'
                                    data to any third party app
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-3 col-lg-2 offset-lg-1">
                                <img class="icon last" src="{{asset('images/about-us/excellent-support.png')}}" alt="Excellent Support" />
                            </div>
                            <div class="col-sm-8">
                                <h5 class="font-weight-bold">Excellent Support</h5>
                                <div class="text">
                                    We develop posh apps by maintaining the utmost degree of client satisfaction, their interest and desires at the forefront of
                                    all our thoughts, and devote the energy, resources and commitment required
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="new-square-box-holder mt-5">
                    <div class="square-columns aos-init aos-animate" data-aos="fade-up" data-aos-duration="1200">
                        <div class="new-square">
                            <div class="new-square-icon apps-bg"></div>
                        </div>
                        <div class="content">
                            <div data-table="blocks" data-column="block_html" data-pk="block_title" data-id="app-count" class="nicEditSubject" id="app-count"><span>28+ Apps</span></div>
                        </div>
                    </div>
                    <div class="square-columns aos-init aos-animate" data-aos="fade-up" data-aos-duration="1500">
                        <div class="new-square">
                            <div class="new-square-icon" style="background-image: url('http://braincraftapps.com/assets/site/images/user.png')"></div>
                        </div>
                        <div class="content">
                            <div data-table="blocks" data-column="block_html" data-pk="block_title" data-id="user-count" class="nicEditSubject" id="user-count"><span>20M Users</span></div>
                        </div>
                    </div>
                    <div class="square-columns aos-init aos-animate" data-aos="fade-up" data-aos-duration="1800">
                        <div class="new-square">
                            <div class="new-square-icon" style="background-image: url('http://braincraftapps.com/assets/site/images/download.png')"></div>
                        </div>
                        <div class="content">
                            <div data-table="blocks" data-column="block_html" data-pk="block_title" data-id="download-count" class="nicEditSubject" id="download-count"><span>30M Downloads</span></div>
                        </div>
                    </div>
                    <div class="square-columns aos-init aos-animate" data-aos="fade-up" data-aos-duration="2100">
                        <div class="new-square">
                            <div class="new-square-icon" style="background-image: url('http://braincraftapps.com/assets/site/images/topchart.png')"></div>
                        </div>
                        <div class="content">
                            <div data-table="blocks" data-column="block_html" data-pk="block_title" data-id="topchart-count" class="nicEditSubject" id="topchart-count"><span>8 Top Charted</span></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
