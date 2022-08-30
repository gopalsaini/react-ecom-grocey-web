import React,{useEffect, useState, useContext} from "react";
import {Link } from "react-router-dom";
import toast, { Toaster } from 'react-hot-toast';
import {BaseUrlContext} from './App';

const Footer = () => {

    useEffect(() => {
        
        const loader = document.getElementById('preloaderNewsletter');

        if(Isloading){
            loader.style.display = "block";
        }else{
            loader.style.display = "none";
        }
    });

    const base_url = useContext(BaseUrlContext);
    const [Isloading, setIsLoaded] = useState(false);
    

    const handleSubmit = async e => {
        e.preventDefault();
        const data =new FormData(e.target);
        const email = data.get('email');
        setIsLoaded(true);
        
        fetch(`${base_url}/newsletter-subscribe`,{
        method: 'POST',
        headers: {
            Accept: 'application/json',
                    'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            email
          })
        }).then(res => res.json())
        .then(
            (result) => {
                setIsLoaded(false);
                console.log(result.data);
            if(result.error){
                toast.error(result.message, {position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'red',secondary: '#fff',},})
                            
            }else{
                toast.success(result.message, {position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                
            }
            
            },
            (error) => {
                setIsLoaded(false);
                toast.error(error, {position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'red',secondary: '#fff',},})

            }
        )
        
    }


    return (
        <>
            
            <footer className="main">
                <section className="newsletter mb-15 wow animate__animated animate__fadeIn">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-12">
                                <div className="position-relative newsletter-inner">
                                    <div className="newsletter-content">
                                        <h2 className="mb-20">
                                            Stay home & get your daily <br /> needs from our shop
                                        </h2>
                                        <p className="mb-45">Start You'r Daily Shopping with <span className="text-brand">Nest Mart</span></p>
                                        <form className="form-subcriber d-flex" id="contact-form" onSubmit={handleSubmit} method="post">
                                            <input type="email" placeholder="Your emaill address" name="email" required />
                                            <button className="btn" type="submit" style={{display:"flex"}}>Subscribe 
                                                <div class="spinner-border " id="preloaderNewsletter" role="status" style={{display:"none"}}>
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </button>
                                        </form>
                                    </div>
                                    <img src="/assets/imgs/banner/banner-9.png" alt="newsletter" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section className="featured section-padding">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-1-5 col-md-4 col-12 col-sm-6 mb-md-4 mb-xl-0">
                                <div className="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay="0">
                                    <div className="banner-icon">
                                        <img src="/assets/imgs/theme/icons/icon-1.svg" alt="" />
                                    </div>
                                    <div className="banner-text">
                                        <h3 className="icon-box-title">Best prices & offers</h3>
                                        <p>Orders $50 or more</p>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-1-5 col-md-4 col-12 col-sm-6">
                                <div className="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                                    <div className="banner-icon">
                                        <img src="/assets/imgs/theme/icons/icon-2.svg" alt="" />
                                    </div>
                                    <div className="banner-text">
                                        <h3 className="icon-box-title">Free delivery</h3>
                                        <p>24/7 amazing services</p>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-1-5 col-md-4 col-12 col-sm-6">
                                <div className="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                                    <div className="banner-icon">
                                        <img src="/assets/imgs/theme/icons/icon-3.svg" alt="" />
                                    </div>
                                    <div className="banner-text">
                                        <h3 className="icon-box-title">Great daily deal</h3>
                                        <p>When you sign up</p>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-1-5 col-md-4 col-12 col-sm-6">
                                <div className="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
                                    <div className="banner-icon">
                                        <img src="/assets/imgs/theme/icons/icon-4.svg" alt="" />
                                    </div>
                                    <div className="banner-text">
                                        <h3 className="icon-box-title">Wide assortment</h3>
                                        <p>Mega Discounts</p>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-1-5 col-md-4 col-12 col-sm-6">
                                <div className="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay=".4s">
                                    <div className="banner-icon">
                                        <img src="/assets/imgs/theme/icons/icon-5.svg" alt="" />
                                    </div>
                                    <div className="banner-text">
                                        <h3 className="icon-box-title">Easy returns</h3>
                                        <p>Within 30 days</p>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-1-5 col-md-4 col-12 col-sm-6 d-xl-none">
                                <div className="banner-left-icon d-flex align-items-center wow animate__animated animate__fadeInUp" data-wow-delay=".5s">
                                    <div className="banner-icon">
                                        <img src="/assets/imgs/theme/icons/icon-6.svg" alt="" />
                                    </div>
                                    <div className="banner-text">
                                        <h3 className="icon-box-title">Safe delivery</h3>
                                        <p>Within 30 days</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section className="section-padding footer-mid" style={{background: "#d8f1e5"}}>
                    <div className="container pt-15 pb-20">
                        <div className="row">
                            <div className="col">
                                <div className="widget-about font-md mb-md-3 mb-lg-3 mb-xl-0 wow animate__animated animate__fadeInUp" data-wow-delay="0">
                                    <div className="logo mb-30">
                                        <a href="index.html" className="mb-15"><img src="/assets/imgs/theme/logo.svg" alt="logo" /></a>
                                        <p className="font-lg text-heading">Awesome grocery store website template</p>
                                    </div>
                                    <h6>Follow Us</h6><br />
                                    <div className="mobile-social-icon " style={{justifyContent: "flex-start"}}>
                                        
                                        <a href="# "><img src="/assets/imgs/theme/icons/icon-facebook-white.svg " alt=" " /></a>
                                        <a href="# "><img src="/assets/imgs/theme/icons/icon-twitter-white.svg " alt=" " /></a>
                                        <a href="# "><img src="/assets/imgs/theme/icons/icon-instagram-white.svg " alt=" " /></a>
                                        <a href="# "><img src="/assets/imgs/theme/icons/icon-pinterest-white.svg " alt=" " /></a>
                                        <a href="# "><img src="/assets/imgs/theme/icons/icon-youtube-white.svg " alt=" " /></a>
                                    </div>
                                </div>
                            </div>
                            <div className="footer-link-widget col wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                                <h4 className=" widget-title ">Company</h4>
                                <ul className="footer-list mb-sm-5 mb-md-0 ">
                                    <li><Link to="/about">About Us</Link></li>
                                    <li><Link to="">Delivery Information</Link></li>
                                    <li><a href="# ">Privacy Policy</a></li>
                                    <li><a href="# ">Terms &amp; Conditions</a></li>
                                    <li><Link to="/contact">Contact Us</Link></li>
                                    <li><a href="# ">Support Center</a></li>
                                </ul>
                            </div>
                            <div className="footer-link-widget col wow animate__animated animate__fadeInUp " data-wow-delay=".2s ">
                                <h4 className="widget-title ">Account</h4>
                                <ul className="footer-list mb-sm-5 mb-md-0 ">
                                    <li><a href="# ">Sign In</a></li>
                                    <li><a href="# ">View Cart</a></li>
                                    <li><a href="# ">My Wishlist</a></li>
                                    <li><a href="# ">Track My Order</a></li>
                                    <li><a href="# ">Help Ticket</a></li>
                                    <li><a href="# ">Shipping Details</a></li>
                                    <li><a href="# ">Compare products</a></li>
                                </ul>
                            </div>
                            <div className="footer-link-widget col wow animate__animated animate__fadeInUp " data-wow-delay=".4s ">
                                <h4 className="widget-title ">Address</h4>
                                <ul className="contact-infor">
                                    <li><img src="assets/imgs/theme/icons/icon-location.svg" alt="" /><strong>Address: </strong> <span>5171 W Campbell Ave undefined Kent, Utah 53127 United States</span></li>
                                    <li><img src="assets/imgs/theme/icons/icon-contact.svg" alt="" /><strong>Call Us:</strong><span>(+91) - 540-025-124553</span></li>
                                    <li><img src="assets/imgs/theme/icons/icon-email-2.svg" alt="" /><strong>Email:</strong><span><a href="/cdn-cgi/l/email-protection" className="__cf_email__" data-cfemail="80f3e1ece5c0cee5f3f4aee3efed">Email</a></span></li>
                                    <li><img src="assets/imgs/theme/icons/icon-clock.svg" alt="" /><strong>Hours:</strong><span>10:00 - 18:00, Mon - Sat</span></li>
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                </section>
                
            </footer>
            
        </>
    )
}

export default Footer;