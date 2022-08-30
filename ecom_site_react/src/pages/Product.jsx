import React,{useContext,useState,useEffect} from "react";
import fetch from 'node-fetch';
import { useSsrState, useSsrEffect } from '@issr/core';
import {Link,useParams } from "react-router-dom";
import MetaTags from 'react-meta-tags';
import {BaseUrlContext} from '../App';
import ReactImageZoom from 'react-image-zoom';
import Slider from "react-slick";
import CategoryLoging from './loadingPage/CategoryLoging'

import AddToCart from './cart/AddToCart';

const Product = ()=>{

    const [cartData, setCartData] = useState([]);
    const base_url = useContext(BaseUrlContext);
    const [isLoaded, setIsLoaded] = useState(false);
    const [imgData, setImg] = useState([]);
    const [singleImage, setSingleImg] = useState('/assets/imgs/shop/product-16-2.jpg');
    const [getSingle, setSingle] = useSsrState([]);
    const [topSellingProduct, setTopSellingProduct] = useSsrState([]);
    const params = useParams();
    const [qtyIncre, setQty] = useState(1);

    const settings1 = {
        dots: false,
        infinite: true,
        speed: 500,
        autoplay: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        initialSlide: 0,
        
      };
      var productSettings = {
        dots: false,
        infinite: true,
        speed: 500,
        autoplay: true,
        slidesToShow: 4,
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
      
    const getSinngleProduct = () => {
        
        return fetch(`${base_url}/product-detail?slug=${params.slug}`)
        .then(data => data.json())
        
    }; 
    
    useEffect(() => {
        setQty(0);
        window.scrollTo({top: 0, behavior: 'smooth'});
        fetch(`${base_url}/product-detail?slug=${params.slug}`).then(res => res.json())
        .then((result) => {
                
            setSingle(result);
            setImg(result.image);
            }
        )
        
    },[params.slug]);

    const getTopSellingProduct = () => {

        return fetch(`${base_url}/topselling-product`)
        .then(data => data.json())
        
    }; 
    
    useSsrEffect(async () => {
        const data = await getSinngleProduct();
        setSingle(data);
        setImg(data.image);

        const dataTopSellingProduct = await getTopSellingProduct()
        setTopSellingProduct(dataTopSellingProduct.result);

        
    });

    useEffect(() => {
       if(imgData.length >0){
            setSingleImg(imgData[0].img);
       }else{
            setSingleImg('/assets/imgs/shop/product-16-2.jpg');
       } 
        
    }, [imgData])

    const SingleImageChnageZoomer = (img)=>{
        setSingleImg(img);
    }

     
    const addToCart2 = (product_id,name,price,img,quantity) =>{
        
        setCartData({product_id,name,price,img,quantity});
         
    }

    
    const QtyIncrement = (qty) =>{

        const quantity = qty+1;
        if(getSingle.stock >= quantity){

            setQty(quantity);
        }
        
         
    }

    
    const QtyDecrement = (qty) =>{
        
        const quantity = qty-1;
        if(quantity >= 1){
            
            setQty(quantity);
        }
        
         
    }

    return(
        <>
            {cartData.length != 0 ? <AddToCart data={cartData}/> :<></>}
            <MetaTags>
                <title>{getSingle.name}</title>
                <meta name="description" content={getSingle.name} />
            </MetaTags>
           <main className="main">
                
                <div className="container mb-30">
                    <div className="row">
                        <div className="col-xl-10 col-lg-12 m-auto">
                            <div className="product-detail accordion-detail">
                                <div className="row mb-50 mt-30">
                                    <div className="col-md-6 col-sm-12 col-xs-12 mb-md-0 mb-sm-5">
                                        <div className="detail-gallery">
                                            
                                            <ReactImageZoom {...{width: 800, height: 500, zoomWidth: 500, img: singleImage}} />
                                            <div className=" d-flex ">
                                                { imgData.length != 0 ? 
                                                    
                                                    imgData.map((item, index) => (
                                                        
                                                        <figure key={index} className="border-radius-10">
                                                            <img onClick={() => SingleImageChnageZoomer(item.img)}  src={item.img} alt="product image" style={{width: "80px",padding: "10px"}}/>
                                                        </figure>
                                                        
                                                    ))
                                                : ''}
                                            </div>
                                        </div>
                                        
                                    </div>
                                   
                                    <div className="col-md-6 col-sm-12 col-xs-12">
                                        <div className="detail-info pr-30 pl-30">
                                            <span className="stock-status out-stock"> Sale Off </span>
                                            
                                            <h2 className="title-detail">{getSingle.name}</h2>
                                            <div className="product-detail-rating">
                                                <div className="product-rate-cover text-end">
                                                    <div className="product-rate d-inline-block">
                                                        <div className="product-rating" style={{width: "90%"}}></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="clearfix product-price-cover">
                                                <div className="product-price primary-color float-left">
                                                    <span className="current-price text-brand">Rs. {getSingle.offer_price}</span>
                                                    <span>
                                                        <span className="save-price font-md color3 ml-15">{ getSingle.discount_type == 1 ?  getSingle.discount_amount+`% off` : `Rs. `+getSingle.discount_amount+` off`}</span>
                                                    <span className="old-price font-md ml-15">Rs. {getSingle.sale_price}</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div className="short-desc mb-30">
                                                <p className="font-lg">{getSingle.short_description}</p>
                                            </div>
                                            <div className="attr-detail attr-size mb-30">
                                                <strong className="mr-10">Size / Weight: </strong>
                                                <ul className="list-filter size-filter font-small">
                                                    <li><a href="#">{getSingle.products_unit}</a></li>
                                                </ul>
                                            </div>
                                            <div className="detail-extralink mb-50">
                                                <div className="detail-qty border radius text-center">
                                                    <a href="#" onClick={(e) => { e.preventDefault(); QtyDecrement(qtyIncre)}} style={{right:"70%"}}><i className="fa fa-minus"></i></a>
                                                        <span className="qty-val" >{qtyIncre}</span>
                                                    <a href="#" onClick={(e) => { e.preventDefault(); QtyIncrement(qtyIncre)}} ><i className="fa fa-plus"></i></a>
                                                </div>
                                                <div className="product-extra-link2">
                                                    <button type="button" className="button button-add-to-cart" onClick={(e) => { e.preventDefault(); addToCart2(getSingle.id,getSingle.name,getSingle.offer_price,getSingle.first_image,qtyIncre)}} ><i className="fa fa-shopping-cart"></i>Add to cart</button>
                                                </div>
                                            </div>
                                            <div className="font-xs">
                                                
                                                <ul className="float-start">
                                                    {/* <li className="mb-5">SKU: <a href="#">FWM15VKT</a></li>
                                                    <li className="mb-5">Tags: <a href="#" rel="tag">Snack</a>, <a href="#" rel="tag">Organic</a>, <a href="#" rel="tag">Brown</a></li> */}
                                                    <li>Stock:<span className="in-stock text-brand ml-5">{getSingle.stock} Items In Stock</span></li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div className="product-info">
                                    <div className="tab-style3">
                                        <ul className="nav nav-tabs text-uppercase">
                                            <li className="nav-item">
                                                <a className="nav-link active" id="Description-tab" data-bs-toggle="tab" href="#Description">Description</a>
                                            </li>
                                            {/* <li className="nav-item">
                                                <a className="nav-link" id="Additional-info-tab" data-bs-toggle="tab" href="#Additional-info">Additional info</a>
                                            </li>
                                            <li className="nav-item">
                                                <a className="nav-link" id="Vendor-info-tab" data-bs-toggle="tab" href="#Vendor-info">Vendor</a>
                                            </li>
                                            <li className="nav-item">
                                                <a className="nav-link" id="Reviews-tab" data-bs-toggle="tab" href="#Reviews">Reviews (3)</a>
                                            </li> */}
                                        </ul>
                                        <div className="tab-content shop_info_tab entry-main-content">
                                            <div className="tab-pane fade show active" id="Description">
                                                <div className="">
                                                    <div dangerouslySetInnerHTML={{__html: getSingle.description}}></div>

                                                </div>
                                            </div>
                                            {/* <div className="tab-pane fade" id="Additional-info">
                                                <table className="font-md">
                                                    <tbody>
                                                        <tr className="stand-up">
                                                            <th>Stand Up</th>
                                                            <td>
                                                                <p>35″L x 24″W x 37-45″H(front to back wheel)</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="folded-wo-wheels">
                                                            <th>Folded (w/o wheels)</th>
                                                            <td>
                                                                <p>32.5″L x 18.5″W x 16.5″H</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="folded-w-wheels">
                                                            <th>Folded (w/ wheels)</th>
                                                            <td>
                                                                <p>32.5″L x 24″W x 18.5″H</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="door-pass-through">
                                                            <th>Door Pass Through</th>
                                                            <td>
                                                                <p>24</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="frame">
                                                            <th>Frame</th>
                                                            <td>
                                                                <p>Aluminum</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="weight-wo-wheels">
                                                            <th>Weight (w/o wheels)</th>
                                                            <td>
                                                                <p>20 LBS</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="weight-capacity">
                                                            <th>Weight Capacity</th>
                                                            <td>
                                                                <p>60 LBS</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="width">
                                                            <th>Width</th>
                                                            <td>
                                                                <p>24″</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="handle-height-ground-to-handle">
                                                            <th>Handle height (ground to handle)</th>
                                                            <td>
                                                                <p>37-45″</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="wheels">
                                                            <th>Wheels</th>
                                                            <td>
                                                                <p>12″ air / wide track slick tread</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="seat-back-height">
                                                            <th>Seat back height</th>
                                                            <td>
                                                                <p>21.5″</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="head-room-inside-canopy">
                                                            <th>Head room (inside canopy)</th>
                                                            <td>
                                                                <p>25″</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="pa_color">
                                                            <th>Color</th>
                                                            <td>
                                                                <p>Black, Blue, Red, White</p>
                                                            </td>
                                                        </tr>
                                                        <tr className="pa_size">
                                                            <th>Size</th>
                                                            <td>
                                                                <p>M, S</p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div className="tab-pane fade" id="Vendor-info">
                                                <div className="vendor-logo d-flex mb-30">
                                                    <img src="/assets/imgs/vendor/vendor-18.svg" alt="" />
                                                    <div className="vendor-name ml-15">
                                                        <h6>
                                                            <a href="vendor-details-2.html">Noodles Co.</a>
                                                        </h6>
                                                        <div className="product-rate-cover text-end">
                                                            <div className="product-rate d-inline-block">
                                                                <div className="product-rating" style={{width: "90%"}}></div>
                                                            </div>
                                                            <span className="font-small ml-5 text-muted"> (32 reviews)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul className="contact-infor mb-50">
                                                    <li><img src="/assets/imgs/theme/icons/icon-location.svg" alt="" /><strong>Address: </strong> <span>5171 W Campbell Ave undefined Kent, Utah 53127 United States</span></li>
                                                    <li><img src="/assets/imgs/theme/icons/icon-contact.svg" alt="" /><strong>Contact Seller:</strong><span>(+91) - 540-025-553</span></li>
                                                </ul>
                                                <div className="d-flex mb-55">
                                                    <div className="mr-30">
                                                        <p className="text-brand font-xs">Rating</p>
                                                        <h4 className="mb-0">92%</h4>
                                                    </div>
                                                    <div className="mr-30">
                                                        <p className="text-brand font-xs">Ship on time</p>
                                                        <h4 className="mb-0">100%</h4>
                                                    </div>
                                                    <div>
                                                        <p className="text-brand font-xs">Chat response</p>
                                                        <h4 className="mb-0">89%</h4>
                                                    </div>
                                                </div>
                                                <p>Noodles & Company is an American fast-casual restaurant that offers international and American noodle dishes and pasta in addition to soups and salads. Noodles & Company was founded in 1995 by Aaron Kennedy and
                                                    is headquartered in Broomfield, Colorado. The company went public in 2013 and recorded a $457 million revenue in 2017.In late 2018, there were 460 Noodles & Company locations across 29 states and Washington,
                                                    D.C.</p>
                                            </div>
                                            <div className="tab-pane fade" id="Reviews">
                                                
                                                <div className="comments-area">
                                                    <div className="row">
                                                        <div className="col-lg-8">
                                                            <h4 className="mb-30">Customer questions & answers</h4>
                                                            <div className="comment-list">
                                                                <div className="single-comment justify-content-between d-flex mb-30">
                                                                    <div className="user justify-content-between d-flex">
                                                                        <div className="thumb text-center">
                                                                            <img src="/assets/imgs/blog/author-2.png" alt="" />
                                                                            <a href="#" className="font-heading text-brand">Sienna</a>
                                                                        </div>
                                                                        <div className="desc">
                                                                            <div className="d-flex justify-content-between mb-10">
                                                                                <div className="d-flex align-items-center">
                                                                                    <span className="font-xs text-muted">December 4, 2022 at 3:12 pm </span>
                                                                                </div>
                                                                                <div className="product-rate d-inline-block">
                                                                                    <div className="product-rating" style={{width: "100%"}}></div>
                                                                                </div>
                                                                            </div>
                                                                            <p className="mb-10">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus, suscipit exercitationem accusantium obcaecati quos voluptate nesciunt facilis itaque modi commodi dignissimos sequi
                                                                                repudiandae minus ab deleniti totam officia id incidunt? <a href="#" className="reply">Reply</a></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div className="single-comment justify-content-between d-flex mb-30 ml-30">
                                                                    <div className="user justify-content-between d-flex">
                                                                        <div className="thumb text-center">
                                                                            <img src="/assets/imgs/blog/author-3.png" alt="" />
                                                                            <a href="#" className="font-heading text-brand">Brenna</a>
                                                                        </div>
                                                                        <div className="desc">
                                                                            <div className="d-flex justify-content-between mb-10">
                                                                                <div className="d-flex align-items-center">
                                                                                    <span className="font-xs text-muted">December 4, 2022 at 3:12 pm </span>
                                                                                </div>
                                                                                <div className="product-rate d-inline-block">
                                                                                    <div className="product-rating" style={{width: "80%"}}></div>
                                                                                </div>
                                                                            </div>
                                                                            <p className="mb-10">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus, suscipit exercitationem accusantium obcaecati quos voluptate nesciunt facilis itaque modi commodi dignissimos sequi
                                                                                repudiandae minus ab deleniti totam officia id incidunt? <a href="#" className="reply">Reply</a></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div className="single-comment justify-content-between d-flex">
                                                                    <div className="user justify-content-between d-flex">
                                                                        <div className="thumb text-center">
                                                                            <img src="/assets/imgs/blog/author-4.png" alt="" />
                                                                            <a href="#" className="font-heading text-brand">Gemma</a>
                                                                        </div>
                                                                        <div className="desc">
                                                                            <div className="d-flex justify-content-between mb-10">
                                                                                <div className="d-flex align-items-center">
                                                                                    <span className="font-xs text-muted">December 4, 2022 at 3:12 pm </span>
                                                                                </div>
                                                                                <div className="product-rate d-inline-block">
                                                                                    <div className="product-rating" style={{width: "80%"}}></div>
                                                                                </div>
                                                                            </div>
                                                                            <p className="mb-10">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus, suscipit exercitationem accusantium obcaecati quos voluptate nesciunt facilis itaque modi commodi dignissimos sequi
                                                                                repudiandae minus ab deleniti totam officia id incidunt? <a href="#" className="reply">Reply</a></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div className="col-lg-4">
                                                            <h4 className="mb-30">Customer reviews</h4>
                                                            <div className="d-flex mb-30">
                                                                <div className="product-rate d-inline-block mr-15">
                                                                    <div className="product-rating" style={{width: "90%"}}></div>
                                                                </div>
                                                                <h6>4.8 out of 5</h6>
                                                            </div>
                                                            <div className="progress">
                                                                <span>5 star</span>
                                                                <div className="progress-bar" role="progressbar" style={{width: "50%"}} aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
                                                            </div>
                                                            <div className="progress">
                                                                <span>4 star</span>
                                                                <div className="progress-bar" role="progressbar" style={{width: "25%"}} aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                                                            </div>
                                                            <div className="progress">
                                                                <span>3 star</span>
                                                                <div className="progress-bar" role="progressbar" style={{width: "45%"}} aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">45%</div>
                                                            </div>
                                                            <div className="progress">
                                                                <span>2 star</span>
                                                                <div className="progress-bar" role="progressbar" style={{width: "65%"}} aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">65%</div>
                                                            </div>
                                                            <div className="progress mb-30">
                                                                <span>1 star</span>
                                                                <div className="progress-bar" role="progressbar" style={{width: "85%"}} aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85%</div>
                                                            </div>
                                                            <a href="#" className="font-xs text-muted">How are ratings calculated?</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div className="comment-form">
                                                    <h4 className="mb-15">Add a review</h4>
                                                    <div className="product-rate d-inline-block mb-30"></div>
                                                    <div className="row">
                                                        <div className="col-lg-8 col-md-12">
                                                            <form className="form-contact comment_form" action="#" id="commentForm">
                                                                <div className="row">
                                                                    <div className="col-12">
                                                                        <div className="form-group">
                                                                            <textarea className="form-control w-100" name="comment" id="comment" cols="30" rows="9" placeholder="Write Comment"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-sm-6">
                                                                        <div className="form-group">
                                                                            <input className="form-control" name="name" id="name" type="text" placeholder="Name" />
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-sm-6">
                                                                        <div className="form-group">
                                                                            <input className="form-control" name="email" id="email" type="email" placeholder="Email" />
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-12">
                                                                        <div className="form-group">
                                                                            <input className="form-control" name="website" id="website" type="text" placeholder="Website" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div className="form-group">
                                                                    <button type="submit" className="button button-contactForm">Submit Review</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> */}
                                        </div>
                                    </div>
                                </div>
                                <div className="row mt-60">
                                    <div className="col-12">
                                        <h2 className="section-title style-1 mb-30">Related products</h2>
                                    </div>
                                    <div className="col-12">
                                        <div className="row related-products">
                                            { topSellingProduct.length != 0 ? 
                                                <Slider {...productSettings}>
                                                    { topSellingProduct.map((item, index) => (
                
                                                        <div key={index} className="col-lg-3 col-md-4 col-12 col-sm-6 pr-15">
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
                                                                            <a className="add" href="shop-cart.html"><i className="fi-rs-shopping-cart mr-5"></i>Add </a>
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
                        </div>
                    </div>
                </div>
            </main>
        </>
    )
}
export default Product;