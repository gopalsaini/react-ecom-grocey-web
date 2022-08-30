import React,{useContext, useEffect,useState} from "react";
import {Link, useParams} from "react-router-dom";
import { useSsrState, useSsrEffect } from '@issr/core';
import {BaseUrlContext} from '../App';
import MetaTags from 'react-meta-tags';
import fetch from 'node-fetch';
import InfiniteScroll from "react-infinite-scroll-component";
import CategoryLoging from './loadingPage/CategoryLoging'

import AddToCart from './cart/AddToCart';


const Products = ()=>{

    const [cartData, setCartData] = useState([]);
    const params = useParams();
    const [products, setProducts] = useState([]);
    let [isNext, isNextFunc] = useState(false);
    const [offset, setPage] = useState(0);
    let [totalProducts, setTotalProducts] = useState(0);
    const [isLoaded, setIsLoaded] = useState(false);
    const [category, setCategory] = useSsrState([]);
    const [brand, setbrand] = useState([]);
    const [brandChecked, setBrandChecked] = useState();
    const base_url = useContext(BaseUrlContext);

    useEffect(() => {
        window.scrollTo({top: 0, behavior: 'smooth'});
        
    },[]);

    
    const fetchData = () => {
        setIsLoaded(true);
        
        
          fetch(`${base_url}/all-product-list?offset=${offset}&limit=12&category=${params.category}&brand=${brandChecked}`, {
              method: "GET",
              headers: {
                      Accept: "application/json",
                      "Content-Type": "application/json",
              },
          }).then(res => res.json())
          .then(
              (result) => {
                  setIsLoaded(false);
                  if(!result.error){
                    
                    setProducts([...products, ...result.result]);
                    isNextFunc(true);
                    setTotalProducts(result.total);
                  }else{
                      

                  }
              
              }
          )
        
    };

    function fetchMoreData() {
        
        if((totalProducts >= products.length) || totalProducts==0){
            
            setPage(offset + 12);
            
            fetchData();
        }
        
    }
  
     
    useEffect(() => {
        
        fetchData();
        
    }, [params.category,brandChecked]);

    
    
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

    
    useEffect( () => {
        
        fetch(`${base_url}/brand-list`, {
            method: "GET",
            headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
            },
        }).then(res => res.json())
        .then(
            (result) => {
                
                if(!result.error){
                    setbrand(result.result);
                }else{
                    

                }
            
            }
        )
       
    }, []);

    const addToCart2 = (product_id,name,price,img) =>{
        
        setCartData({product_id,name,price,img});
         
    }

    const brandGet = () =>{
        
        setBrandChecked($('.brand:checked').map(function () { return this.value; }).get().join(','));
       
    }

      
    // useEffect(() => {
    //     setPage(0);
    //     setTotalProducts(0);
    //     getProduct();
    //   }, []);

    return(
        <>
            {cartData.length != 0 ? <AddToCart data={cartData}/> :<></>}
             <MetaTags>
                <title>Product Page</title>
                <meta name="description" content="Product page" />
            </MetaTags>
            <main className="main mt-30">
               
                <div className="container mb-30">
                    <div className="row flex-row-reverse">
                        <div className="col-lg-4-5">
                            <div className="shop-product-fillter">
                                <div className="totall-product">
                                    <p>We found <strong className="text-brand">{products.length}</strong> items for you!</p>
                                </div>
                                <div className="sort-by-product-area">
                                    
                                    <div className="sort-by-cover">
                                        
                                        <select class="form-control sort_order" name="sort_by" >
                                            <option value="" selected=""> -- Sort By -- </option>
                                            <option value="1">Price: Low to High</option>
                                            <option value="2">Price: High to Low</option>
                                            <option value="3">Order: ASC</option>
                                            <option value="4">Order: DESC</option>
                                        </select>                                   
                                        
                                    </div>
                                </div>
                            </div>
                            <div className="row product-grid">
                                { isLoaded == false ? 
                                
                                products.length ? products.map((item, index) => (
            
                                    <div key={index} className="col-lg-1-4 col-md-3 col-12 col-sm-6 mb-25">
                                        <div className="product-cart-wrap ">
                                            <div className="product-img-action-wrap">
                                                <div className="product-img product-img-zoom">
                                                    <Link to={`/product/${item.slug}`}>
                                                        <img className="default-img" src={item.first_image} style={{height: "220px"}} />
                                                        <img className="hover-img" src={item.second_image} alt="" style={{height: "220px"}}/>
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
                                                        <a className="add" href="" onClick={(e) => { e.preventDefault(); addToCart2(item.id,item.name,item.offer_price,item.first_image)}} ><i className="fa fa-shopping-cart mr-5"></i>Add </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    )) : <><img className="img-fluid" src="http://localhost/react/prince_ecomm_react/prince_app/images/nproduct.png" alt="" /></>

                                : <><CategoryLoging /><CategoryLoging /></>}
                                <InfiniteScroll
                                    dataLength={products.length} //sets the length of data
                                    next={fetchMoreData} //fetching next set of data
                                    hasMore={isNext} //determine is more data is there to load
                                    loader={!isLoaded ? 
                                            <></>
                                        :
                                            <div className="row product-box listing-page">
                                                <CategoryLoging />
                                            </div>
                                        
                                    }
                                    //is displayed when more data is loaded
                                />
                            </div>
                            
                        </div>
                        <div className="col-lg-1-5 primary-sidebar sticky-sidebar">
                            <div className="sidebar-widget widget-category-2 mb-30">
                                <h5 className="section-title style-1 mb-30">Category</h5>
                                <ul style={{height: "400px",overflow: "scroll"}}>
                                    { category.length != 0 ? category.map((item, index) => (

                                        <li key={index}>
                                            <a href={`/products/${item.slug}`} key={index}> <img src={item.image} alt="" />{item.name}</a>
                                        </li>
                                        )) : '' 
                                    }
                                </ul>
                            </div>
                            
                            <div className="sidebar-widget price_range range mb-30">
                                <h5 className="section-title style-1 mb-30">Brand</h5>
                                
                                <div className="list-group">
                                    <div className="list-group-item mb-10 mt-10">
                                        <div className="custome-checkbox" style={{height: "400px",overflow: "scroll"}}>
                                            { brand.length != 0 ? brand.map((item, index) => (
                                                    <span key={index}>
                                                        <input className="form-check-input brand" type="checkbox" name="brand" id={`exampleCheckbox${index}`} value={item.id} onChange={brandGet}/>
                                                        <label className="form-check-label" htmlFor={`exampleCheckbox${index}`}><span>{item.name}</span></label>
                                                        <br />
                                                    </span>
                                                )) : '' 
                                            }
                                            
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
export default Products;