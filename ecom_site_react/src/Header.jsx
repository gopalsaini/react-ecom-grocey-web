import React,{useContext,useEffect,useState} from "react";
import {Link } from "react-router-dom";

import fetch from 'node-fetch';
import { useSsrState, useSsrEffect } from '@issr/core';
import toast, { Toaster } from 'react-hot-toast';

import {BaseUrlContext, AuthContext, CartContext} from './App';


const Header = () => {

    const base_url = useContext(BaseUrlContext);
    const [category, setCategory] = useState([]);
    const [cart, setCart] = useContext(CartContext);
    const [LoggedIn, setLoggedIn] = useContext(AuthContext);
    const [TotalCart, TotalCartData] = useState([]);
    const [Subtotal, setSubtotal] = useState(0); 
      
    useEffect( () => {
        
        fetch(`${base_url}/category-list`, {
            method: "GET",
            headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
            },
        }).then(res => res.json())
        .then(
            (result) => {
                
                if(!result.error){
                  setCategory(result.result);
                }else{
                    

                }
            
            }
        )
       
    }, []); 

    
    useEffect(() => {
        if(LoggedIn){

            TotalCartData(cart ?? []);
           
        }else{

            if(localStorage.getItem('cart_item')){
                TotalCartData(JSON.parse(localStorage.getItem('cart_item')));
                //console.log(TotalCart)
                let _total = 0;
                TotalCart.forEach((item) => {
                _total += ((item.quantity)*(item.price));
                });
                setSubtotal((_total).toFixed(2)); 
            }
        }
        
    }, [cart])

    
    const removeToCart = (product_id) =>{

        if(product_id) { 

            if(LoggedIn){

                setCartIsLoaded(true);

                fetch(value+'delete-cart',
                {
                    method: "POST",
                    headers: {
                            Accept: "application/json",
                            "Content-Type": "application/json",
                            Authorization: `Bearer ${LoggedIn}`,
                    },
                    body: JSON.stringify({
                        id: product_id,
                    }),
                }).then(res => res.json())
                .then(
                    (result) => {
                        setCartIsLoaded(false);
                        if(result.error){
                            toast.error(result.message, {position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                        }else{
                            
                            toast.success(result.message, {position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                            fetch(value+'cart-list', {
                                method: "GET",
                                headers: {
                                        Accept: "application/json",
                                        "Content-Type": "application/json",
                                        Authorization: `Bearer ${LoggedIn}`,
                                },
                            }).then(res => res.json())
                            .then(
                                (result) => {
                                    setCartIsLoaded(false);
                                    if(!result.error){
                                        if(result.result){
                                            
                                            setCart(result.result);
                                        }
                                        
                                    }else{
                                        setCart(result.result);
                                        TotalCartData(result.result);
                                        setSubtotal((0).toFixed(2)); 
                                    }
                                
                                }
                            )
                        }
                    
                    },
                    (error) => {
                        toast.error(error, {position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                        setCartIsLoaded(false);
                        
                    }
                )

            }else{

                for (var i = TotalCart.length; i--;) {
                    if (TotalCart[i].productid === product_id) TotalCart.splice(i, 1);
                }
    
                setCart(localStorage.setItem("cart_item", JSON.stringify(TotalCart)));
                toast.success('Item removed from cart successfully.',{position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
            }
            
        }

    }

    return (
        <>
            <Toaster />
            <header className="header-area header-style-1 header-height-2">
                
                <div className="header-middle header-middle-ptb-1 d-none d-lg-block">
                    <div className="container">
                        <div className="header-wrap">
                            <div className="logo logo-width-1">
                                <Link to="/"><img src="/assets/imgs/theme/logo.svg" alt="logo" /></Link>
                            </div>
                            <div className="header-right">
                                <div className="search-style-2">
                                    <form action="#">
                                        
                                        <input type="text" placeholder="Search for items..." />
                                    </form>
                                </div>
                                <div className="header-action-right">
                                    <div className="header-action-2">
                                        
                                        
                                        <div className="header-action-icon-2">
                                            <Link to="/wishlist">
                                                <img className="svgInject" alt="Nest" src="/assets/imgs/theme/icons/icon-heart.svg" />
                                                <span className="pro-count blue">6</span>
                                            </Link>
                                            <Link to="/wishlist"><span className="lable">Wishlist</span></Link>
                                        </div>
                                        <div className="header-action-icon-2">
                                            <a className="mini-cart-icon" href="#">
                                                <img alt="Nest" src="/assets/imgs/theme/icons/icon-cart.svg" />
                                                <span className="pro-count blue">{TotalCart.length ?? 0}</span>
                                            </a>
                                            <a href="#"><span className="lable">Cart</span></a>
                                            <div className="cart-dropdown-wrap cart-dropdown-hm2 ">
                                                <ul>
                                                    <div className="row">
                                                    { TotalCart.length != 0 ? TotalCart.map((item, index) => (
                                                        <li >
                                                            <div className="shopping-cart-img col-md-4">
                                                                <a href=""><img alt="Nest" src={item.img} /></a>
                                                            </div>
                                                            <div className="shopping-cart-title col-md-6">
                                                                <h6><a href="">{item.name}</a></h6>
                                                                <h6><span>{item.quantity} × </span>Rs. {item.price}</h6>
                                                            </div>
                                                            <div className="shopping-cart-delete col-md-1">
                                                                <a href="" onClick={(e) => { e.preventDefault(); removeToCart(item.productid)}}><i className="fa fa-trash-o"></i></a>
                                                            </div>
                                                        </li>
                                                    )) : 
                                                    
                                                        <li>
                                                            <div className="thankyou-card">
                                                                <div className="icon-box">
                                                                    <img className="img-fluid" src="http://localhost/react/prince_ecomm_react/prince_app/cartempty.gif" alt="" />
                                                                </div>
                                                                <div className="thankyou-text">
                                                                    <h4><span>Oops!</span> Your cart is empty!</h4>
                                                                    <p>Looks like you haven't added anything to your cart yet.</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    
                                                    }
                                                    </div>
                                                </ul>
                                                <div className="shopping-cart-footer">
                                                    <div className="shopping-cart-total">
                                                        <h4>Total <span>₹ {Subtotal}</span></h4>
                                                    </div>
                                                    <div className="shopping-cart-button">
                                                        <Link to="/cart" className="outline">View cart</Link>
                                                        <Link to="/checkout">Checkout</Link>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="header-action-icon-2">
                                            <a href="page-account.html">
                                                <img className="svgInject" alt="Nest" src="/assets/imgs/theme/icons/icon-user.svg" />
                                            </a>
                                            <a href="page-account.html"><span className="lable ml-0">Account</span></a>
                                            <div className="cart-dropdown-wrap cart-dropdown-hm2 account-dropdown">
                                                <ul>
                                                    <li>
                                                        <Link to="/login"><i className="fi fi-rs-user mr-10"></i>Login</Link>
                                                    </li>
                                                    <li>
                                                        <Link to="/register"><i className="fi fi-rs-user mr-10"></i>Register</Link>
                                                    </li>
                                                    <li>
                                                        <a href="page-account.html"><i className="fi fi-rs-user mr-10"></i>My Account</a>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="page-login.html"><i className="fi fi-rs-sign-out mr-10"></i>Sign out</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="header-bottom header-bottom-bg-color sticky-bar">
                    <div className="container">
                        <div className="header-wrap header-space-between position-relative">
                            <div className="logo logo-width-1 d-block d-lg-none">
                                <a href="index.html"><img src="/assets/imgs/theme/logo.svg" alt="logo" /></a>
                            </div>
                            <div className="header-nav d-none d-lg-flex">
                                <div className="main-categori-wrap d-none d-lg-block">
                                    <a className="categories-button-active" href="#">
                                        <span className="fa fa-apps"></span> <span className="et">Browse</span> All Categories
                                        <i className="fa fa-angle-down"></i>
                                    </a>
                                    <div className="categories-dropdown-wrap categories-dropdown-active-large font-heading">
                                        <div className="d-flex categori-dropdown-inner">
                                        
                                            <ul>
                                                { category.length != 0 ? category.slice(0,8).map((item, index) => (

                                                    <li key={index}>
                                                        <a href={`/products/${item.slug}`} key={index}> <img src={item.image} alt="" />{item.name}</a>
                                                    </li>
                                                    )) : '' }

                                            </ul>
                                            <ul className="end">
                                                { category.length != 0 ? category.slice(8,16).map((item, index) => (

                                                    <li key={index}>
                                                        <a href={`/products/${item.slug}`}> <img src={item.image} alt="" />{item.name}</a>
                                                    </li>
                                                    )) : '' }

                                            </ul>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div className="main-menu main-menu-padding-1 main-menu-lh-2 d-none d-lg-block font-heading">
                                    <nav>
                                        <ul>
                                            <li><Link className="active" to="/">Home </Link></li>
                                            <li><Link to="/about">About</Link></li>
                                            <li><Link to="/products">Products </Link></li>
                                            <li><Link to="/blogs">Blog </Link></li>
                                            <li><Link to="/contact">Contact</Link></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div className="hotline d-none d-lg-flex">
                                <img src="/assets/imgs/theme/icons/icon-headphone.svg" alt="hotline" />
                                <p>7788995566<span>24/7 Support Center</span></p>
                            </div>
                            <div className="header-action-icon-2 d-block d-lg-none">
                                <div className="burger-icon burger-icon-white">
                                    <span className="burger-icon-top"></span>
                                    <span className="burger-icon-mid"></span>
                                    <span className="burger-icon-bottom"></span>
                                </div>
                            </div>
                            <div className="header-action-right d-block d-lg-none">
                                <div className="header-action-2">
                                    {/* <div className="header-action-icon-2">
                                        <a href="shop-wishlist.html">
                                            <img alt="Nest" src="/assets/imgs/theme/icons/icon-heart.svg" />
                                            <span className="pro-count white">4</span>
                                        </a>
                                    </div> */}
                                    <div className="header-action-icon-2">
                                        <a className="mini-cart-icon" href="#">
                                            <img alt="Nest" src="/assets/imgs/theme/icons/icon-cart.svg" />
                                            <span className="pro-count white">{TotalCart.length ?? 0}</span>
                                        </a>
                                        <div className="cart-dropdown-wrap cart-dropdown-hm2">
                                            <ul>
                                                <div className="row">
                                                    { TotalCart.length != 0 ? TotalCart.map((item, index) => (
                                                        <li >
                                                            <div className="shopping-cart-img col-md-4">
                                                                <a href=""><img alt="Nest" src={item.img} /></a>
                                                            </div>
                                                            <div className="shopping-cart-title col-md-6">
                                                                <h6><a href="">{item.name}</a></h6>
                                                                <h6><span>{item.quantity} × </span>Rs. {item.price}</h6>
                                                            </div>
                                                            <div className="shopping-cart-delete col-md-1">
                                                                <a href="" onClick={(e) => { e.preventDefault(); removeToCart(item.productid)}}><i className="fa fa-trash-o"></i></a>
                                                            </div>
                                                        </li>
                                                    )) : 
                                                    
                                                        <li>
                                                            <div className="thankyou-card">
                                                                <div className="icon-box">
                                                                    <img className="img-fluid" src="http://localhost/react/prince_ecomm_react/prince_app/cartempty.gif" alt="" />
                                                                </div>
                                                                <div className="thankyou-text">
                                                                    <h4><span>Oops!</span> Your cart is empty!</h4>
                                                                    <p>Looks like you haven't added anything to your cart yet.</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    
                                                    }
                                                </div>
                                            </ul>
                                            <div className="shopping-cart-footer">
                                                <div className="shopping-cart-total">
                                                    <h4>Total <span>₹ {Subtotal}</span></h4>
                                                </div>
                                                <div className="shopping-cart-button">
                                                    <a href="/cart">View cart</a>
                                                    <a href="/checkout">Checkout</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div className="mobile-header-active mobile-header-wrapper-style">
                <div className="mobile-header-wrapper-inner">
                    <div className="mobile-header-top">
                        <div className="mobile-header-logo">
                            <a href="index.html"><img src="/assets/imgs/theme/logo.svg" alt="logo" /></a>
                        </div>
                        <div className="mobile-menu-close close-style-wrap close-style-position-inherit">
                            <button className="close-style search-close">
                                <i className="icon-top"></i>
                                <i className="icon-bottom"></i>
                            </button>
                        </div>
                    </div>
                    <div className="mobile-header-content-area">
                        <div className="mobile-search search-style-3 mobile-header-border">
                            <form action="#">
                                <input type="text" placeholder="Search for items…" />
                                <button type="submit"><i className="fi-rs-search"></i></button>
                            </form>
                        </div>
                        <div className="mobile-menu-wrap mobile-header-border">
                            <nav>
                                <ul className="mobile-menu font-heading">
                                    <li><Link className="active" to="/">Home </Link></li>
                                    <li><Link to="/about">About</Link></li>
                                    <li><Link to="/products">Products </Link></li>
                                    <li><Link to="/blogs">Blog </Link></li>
                                    <li><Link to="/contact">Contact</Link></li>
                                </ul>
                            </nav>
                        </div>
                        <div className="mobile-header-info-wrap">
                            <div className="single-mobile-header-info">
                                <a href="page-contact.html"><i className="fi-rs-marker"></i> Our location </a>
                            </div>
                            <div className="single-mobile-header-info">
                                <a href="page-login.html"><i className="fi-rs-user"></i>Log In / Sign Up </a>
                            </div>
                            <div className="single-mobile-header-info">
                                <a href="#"><i className="fi-rs-headphones"></i>(+01) - 2345 - 6789 </a>
                            </div>
                        </div>
                        <div className="mobile-social-icon mb-50">
                            <h6 className="mb-15">Follow Us</h6>
                            <a href="#"><img src="assets/imgs/theme/icons/icon-facebook-white.svg" alt="" /></a>
                            <a href="#"><img src="assets/imgs/theme/icons/icon-twitter-white.svg" alt="" /></a>
                            <a href="#"><img src="assets/imgs/theme/icons/icon-instagram-white.svg" alt="" /></a>
                            <a href="#"><img src="assets/imgs/theme/icons/icon-pinterest-white.svg" alt="" /></a>
                            <a href="#"><img src="assets/imgs/theme/icons/icon-youtube-white.svg" alt="" /></a>
                        </div>
                        <div className="site-copyright">Copyright 2022 © Nest. All rights reserved. Powered by AliThemes.</div>
                    </div>
                </div>
            </div>
            
        </>
    )
}

export default Header;