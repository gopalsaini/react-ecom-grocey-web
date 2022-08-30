import React,{useEffect, useContext, useState} from "react";
import fetch from 'node-fetch';
import { useSsrState, useSsrEffect } from '@issr/core';
import MetaTags from 'react-meta-tags';
import {BaseUrlContext, CartContext, AuthContext} from './App';
import CategoryLoging from './pages/loadingPage/CategoryLoging';
import Slider from "react-slick";
import { Link } from "react-router-dom";
import AddToCart from './pages/cart/AddToCart';

// const getTodos = () => {
    
//     return fetch('http://reactapp.babacarbazar.com/laravel/public/api/get-banner')
//      .then(data => data.json())
     
//   };

const Home = ()=>{

    
    var settings = {
        dots: false,
        infinite: true,
        speed: 500,
        autoplay: true,
        slidesToShow: 6,
        slidesToScroll: 1,
        initialSlide: 0,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
              infinite: true,
              dots: true
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
              initialSlide: 2
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      };
       
    var productSettings = {
        dots: false,
        infinite: true,
        speed: 500,
        autoplay: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        initialSlide: 0,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1,
              infinite: true,
              dots: true
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
              initialSlide: 2
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      };

    const base_url = useContext(BaseUrlContext);
    const [cart, setCart] = useContext(CartContext);
    const [LoggedIn, setLoggedIn] = useContext(AuthContext);

    const [cartData, setCartData] = useState([]);

    const [banner, setBanners] = useSsrState([]);
    const [category, setCategory] = useSsrState([]);
    const [mustTyrCategory, setMustTryCategory] = useSsrState([]);
    const [brand, setBrand] = useSsrState([]);
    const [dealOfTheWeekProduct, setDealOfTheWeekProduct] = useSsrState([]);
    const [topSellingProduct, setTopSellingProduct] = useSsrState([]);
   
    const getBanner = () => {

        return fetch(`${base_url}/banner-list`)
        .then(data => data.json())
        
    }; 
    const getCategory = () => {

        return fetch(`${base_url}/category-list`)
        .then(data => data.json())
        
    };  
    const getmustTryCategory = () => {

        return fetch(`${base_url}/must-try-category`)
        .then(data => data.json())
        
    };   
    const getBrand = () => {

        return fetch(`${base_url}/brand-list`)
        .then(data => data.json())
        
    };    
    const getDealOfTheWeekProduct = () => {

        return fetch(`${base_url}/dealsoftheweek-productlist`)
        .then(data => data.json())
        
    };      
    const getTopSellingProduct = () => {

        return fetch(`${base_url}/topselling-product`)
        .then(data => data.json())
        
    };    
    
    useEffect(() => {
        window.scrollTo({top: 0, behavior: 'smooth'});
    },[]);

    useSsrEffect(async () => {
        const data = await getBanner()
        setBanners(data.result);

        const dataCate = await getCategory()
        setCategory(dataCate.result);

        const dataMustTryCate = await getmustTryCategory()
        setMustTryCategory(dataMustTryCate.result);

        const brandData = await getBrand()
        setBrand(brandData.result);

        const dataDealOfTheWeekProduct = await getDealOfTheWeekProduct()
        setDealOfTheWeekProduct(dataDealOfTheWeekProduct.result);

        const dataTopSellingProduct = await getTopSellingProduct()
        setTopSellingProduct(dataTopSellingProduct.result);
    });

    
    const addToCart2 = (product_id,name,price,img) =>{
        
        setCartData({product_id,name,price,img});
         
    }

    return(
        <>
      
            {cartData.length != 0 ? <AddToCart data={cartData}/> :<></>}
            <MetaTags>
                <title>Home Page</title>
                <meta name="description" content="Home page" />
                <script src="/asset/js/owl.carousel.min.js"></script>
            </MetaTags>
            <main className="main">
                <section className="home-slider position-relative mb-30 mt-10">
                    <div className="container">
                        
                        <div id="carouselExampleFade" className="carousel slide carousel-fade" data-bs-ride="carousel" >
                            <div className="carousel-inner">
                            
                                { banner.length != 0 ? banner.map((item, index) => (

                                    <div key={index} className={`carousel-item ${index == 0 ? "active" : ""}`}>
                                        <img src={item.image} className="d-block w-100" alt="slider" />
                                    </div>  
                                )) : <div class="skeleton skeleton-text " style={{height:"478px"}}></div>}
                            
                                                
                            </div>
                            <button className="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                                <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span className="visually-hidden">Previous</span>
                            </button>
                            <button className="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                                <span className="carousel-control-next-icon" aria-hidden="true"></span>
                                <span className="visually-hidden">Next</span>
                            </button> 
                        </div>
                    </div>
                    
                </section>
                
                <section className="popular-categories section-padding">
                    <div className="container wow animate__animated animate__fadeIn">
                        <div className="section-title">
                            <div className="title">
                                <h3>Top Categories</h3>
                                
                            </div>
                            <div className="slider-arrow slider-arrow-2 flex-right carausel-10-columns-arrow" id="carausel-10-columns-arrows"></div>
                        </div>
                        <div className="row">
                            { category.length != 0 ? 
                                <Slider {...settings}>
                                    {category.map((item, index) => (

                                        <div key={index} className="col-md-2 pr-10">
                                            <div className="card-2">
                                                <figure className="img-hover-scale overflow-hidden">
                                                    <Link to={`/products/${item.slug}`}><img src={item.image} alt="" /></Link>
                                                </figure>
                                                <h6><Link to={`/products/${item.slug}`}>{item.name}</Link></h6>
                                                {/* <span>26 items</span> */}
                                            </div>
                                        </div> 
                                    )) }
                                </Slider>
                            : <CategoryLoging />}
                        </div>
                        
                    </div>
                </section>
                
                <section className="banners mb-25">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-4 col-md-6">
                                <div className="banner-img wow animate__animated animate__fadeInUp" data-wow-delay="0">
                                    <img src="assets/imgs/banner/banner-1.png" alt="" />
                                    <div className="banner-text">
                                        <h4>
                                            Everyday Fresh & <br />Clean with Our<br /> Products
                                        </h4>
                                        <a href="shop-grid-right.html" className="btn btn-xs">Shop Now <i className="fi-rs-arrow-small-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-4 col-md-6">
                                <div className="banner-img wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
                                    <img src="assets/imgs/banner/banner-2.png" alt="" />
                                    <div className="banner-text">
                                        <h4>
                                            Make your Breakfast<br /> Healthy and Easy
                                        </h4>
                                        <a href="shop-grid-right.html" className="btn btn-xs">Shop Now <i className="fi-rs-arrow-small-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-4 d-md-none d-lg-flex">
                                <div className="banner-img mb-sm-0 wow animate__animated animate__fadeInUp" data-wow-delay=".4s">
                                    <img src="assets/imgs/banner/banner-3.png" alt="" />
                                    <div className="banner-text">
                                        <h4>The best Organic <br />Products Online</h4>
                                        <a href="shop-grid-right.html" className="btn btn-xs">Shop Now <i className="fi-rs-arrow-small-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section className="product-tabs section-padding position-relative">
                    <div className="container">
                        <div className="section-title style-2 wow animate__animated animate__fadeIn">
                            <h3>Popular Products</h3>
                            <ul className="nav nav-tabs links" id="myTab" role="tablist">
                                <li className="nav-item" role="presentation">
                                    <button className="nav-link active" id="nav-tab-one" data-bs-toggle="tab" data-bs-target="#tab-one" type="button" role="tab" aria-controls="tab-one" aria-selected="true">View All</button>
                                </li>
                            </ul>
                        </div>
                        
                        <div className="tab-content" id="myTabContent">
                            <div className="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                                <div className="row product-grid-4">
                                    { topSellingProduct.length != 0 ? 
                                        <Slider {...productSettings}>
                                            { topSellingProduct.map((item, index) => (
        
                                                <div key={index} className="col-lg-1-5 col-md-4 col-12 col-sm-6 pr-15">
                                                    <div className="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s">
                                                        <div className="product-img-action-wrap">
                                                            <div className="product-img product-img-zoom">
                                                                <Link to={`/product/${item.slug}`}>
                                                                    <img className="default-img" src={item.first_image} alt="" />
                                                                    <img className="hover-img" src={item.second_image} alt="" />
                                                                </Link>
                                                            </div>
                                                            {/* <div className="product-action-1">
                                                                <a aria-label="Add To Wishlist" className="action-btn" href="shop-wishlist.html"><i className="fi-rs-heart"></i></a>
                                                                <a aria-label="Compare" className="action-btn" href="shop-compare.html"><i className="fi-rs-shuffle"></i></a>
                                                                <a aria-label="Quick view" className="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i className="fi-rs-eye"></i></a>
                                                            </div> */}
                                                            <div className="product-badges product-badges-position product-badges-mrg">
                                                                <span className="hot">{ item.discount_type == 1 ?  item.discount_amount+`% off` : `Rs. `+item.discount_amount+` off`}</span>
                                                            </div>
                                                        </div>
                                                        <div className="product-content-wrap">
                                                            <div className="product-category">
                                                                {item.products_qty}
                                                            </div>
                                                            <h2><Link to={`/product/${item.slug}`}>{item.name}</Link></h2>
                                                            
                                                            <div>
                                                                <span className="font-small text-muted">By <a href="#">DOORSTEP GROCERY</a></span>
                                                            </div>
                                                            <div className="product-card-bottom">
                                                                <div className="product-price">
                                                                    <span>Rs.{item.offer_price}</span>
                                                                    <span className="old-price">Rs.{item.sale_price}</span>
                                                                </div>
                                                                <div className="add-cart">
                                                                    <a className="add" href='' style={{display:"flex"}} onClick={(e) => { e.preventDefault(); addToCart2(item.id,item.name,item.offer_price,item.first_image)}} >
                                                                        <i className="fa fa-shopping-cart mr-5"></i>
                                                                        Add 
                                                                        <div class="spinner-border" id="preloader" role="status" style={{display:"none"}}>
                                                                            <span class="sr-only">Loading...</span>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            )) }
                                        </Slider>
                                    : <CategoryLoging />}
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                </section>

                <section className="popular-categories section-padding">
                    <div className="container wow animate__animated animate__fadeIn">
                        <div className="section-title">
                            <div className="title">
                                <h3>Must Try Categories</h3>
                                
                            </div>
                            <div className="slider-arrow slider-arrow-2 flex-right carausel-10-columns-arrow" id="carausel-10-columns-arrows"></div>
                        </div>
                        <div className="row">

                            { mustTyrCategory.length != 0 ? mustTyrCategory.map((item, index) => (

                                <div key={index} className="col-md-2 ">
                                    <div className="card-2 " style={{background: "#1a1b1c00"}}>
                                        <figure className="img-hover-scale overflow-hidden">
                                            <Link to={`/products/${item.slug}`}><img src={item.image} alt="" style={{maxWidth: "149px"}}/></Link>
                                        </figure>
                                        {/* <h6><a href="shop-grid-right.html">{item.name}</a></h6> */}
                                        {/* <span>26 items</span> */}
                                    </div>
                                </div> 
                            )) : <CategoryLoging />}
                            
                               
                        </div>
                        
                    </div>
                </section>

                <section className="product-tabs section-padding position-relative">
                    <div className="container">
                        <div className="section-title style-2 wow animate__animated animate__fadeIn">
                            <h3>Daily Best Sells</h3>
                            <ul className="nav nav-tabs links" id="myTab" role="tablist">
                                <li className="nav-item" role="presentation">
                                    <button className="nav-link active" id="nav-tab-one" data-bs-toggle="tab" data-bs-target="#tab-one" type="button" role="tab" aria-controls="tab-one" aria-selected="true">View All</button>
                                </li>
                            </ul>
                        </div>
                        
                        <div className="tab-content" id="myTabContent">
                            <div className="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                                <div className="row product-grid-4">
                                    { dealOfTheWeekProduct.length != 0 ?
                                        <Slider {...productSettings}>
                                            { dealOfTheWeekProduct.map((item, index) => (
        
                                                <div key={index} className="col-lg-1-5 col-md-4 col-12 col-sm-6 pr-15">
                                                    <div className="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s">
                                                        <div className="product-img-action-wrap">
                                                            <div className="product-img product-img-zoom">
                                                                <Link to={`/product/${item.slug}`}>
                                                                    <img className="default-img" src={item.first_image} alt="" />
                                                                    <img className="hover-img" src={item.second_image} alt="" />
                                                                </Link>
                                                            </div>
                                                            {/* <div className="product-action-1">
                                                                <a aria-label="Add To Wishlist" className="action-btn" href="shop-wishlist.html"><i className="fi-rs-heart"></i></a>
                                                                <a aria-label="Compare" className="action-btn" href="shop-compare.html"><i className="fi-rs-shuffle"></i></a>
                                                                <a aria-label="Quick view" className="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i className="fi-rs-eye"></i></a>
                                                            </div> */}
                                                            <div className="product-badges product-badges-position product-badges-mrg">
                                                                <span className="hot">{ item.discount_type == 1 ?  item.discount_amount+`% off` : `Rs. `+item.discount_amount+` off`}</span>
                                                            </div>
                                                        </div>
                                                        <div className="product-content-wrap">
                                                            <div className="product-category">
                                                                {item.products_qty}
                                                            </div>
                                                            <h2><Link to={`/product/${item.slug}`}>{item.name}</Link></h2>
                                                            
                                                            <div>
                                                                <span className="font-small text-muted">By <a href="#">DOORSTEP GROCERY</a></span>
                                                            </div>
                                                            <div className="product-card-bottom">
                                                                <div className="product-price">
                                                                    <span>Rs.{item.offer_price}</span>
                                                                    <span className="old-price">Rs.{item.sale_price}</span>
                                                                </div>
                                                                <div className="add-cart">
                                                                    <a className="add" href="" onClick={(e) => { e.preventDefault(); addToCart2(item.id,item.name,item.offer_price,item.first_image)}}><i className="fa fa-shopping-cart mr-5"></i> Add </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            )) }
                                        </Slider>
                                    : <CategoryLoging />}
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                </section>

                <section className="banners mb-25">
                    <div className="section-title">
                        <div className="title">
                            <h3>Top Brands</h3>
                            
                        </div>
                        <div className="slider-arrow slider-arrow-2 flex-right carausel-10-columns-arrow" id="carausel-10-columns-arrows"></div>
                    </div>
                    <div className="container">
                        <div className="row">
                            
                            { brand.length != 0 ? brand.map((item, index) => (

                                <div key={index} className="col-lg-3 col-md-3">
                                    <div className="banner-img wow animate__animated animate__fadeInUp" data-wow-delay="0">
                                        <a href="shop-grid-right.html" className="">
                                            <img src={item.image} alt="" />
                                        </a>
                                    </div>
                                </div>
                            )) : <CategoryLoging />}
                            
                        </div>
                    </div>
                </section>
                
            </main>
            
        </>
    )
}
export default Home;