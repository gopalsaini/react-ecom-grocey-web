import React,{useEffect, useContext, useState} from "react";
import {Link } from "react-router-dom";
import { useSsrState, useSsrEffect } from '@issr/core';

import {BaseUrlContext} from '../App';

const Blogs = ()=>{

    
    const base_url = useContext(BaseUrlContext);
    const [blogs, setBlog] = useState([]);

    useEffect(() => {
        window.scrollTo({top: 0, behavior: 'smooth'});
    });

    
    useEffect( () => {
        
        fetch(`${base_url}/blog-list`, {
            method: "GET",
            headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
            },
        }).then(res => res.json())
        .then(
            (result) => {
                
                if(!result.error){
                    setBlog(result.result);
                }else{
                    

                }
            
            }
        )
       
    }, []); 

    return(
        <>
            <main className="main">
                
                <div className="page-content mb-50 mt-50">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-9">
                                <div className="shop-product-fillter mb-50mb-50 pr-30">
                                    <div className="totall-product">
                                        <h2>
                                             Our Blogs
                                        </h2>
                                    </div>
                                    
                                </div>
                                <div className="loop-grid pr-30">
                                    <div className="row">
                                    { blogs.length != 0 ? blogs.map((item, index) => (
                                        <article key={index} className="col-xl-4 col-lg-6 col-md-6 text-center hover-up mb-30 animated">
                                            <div className="post-thumb">
                                                <Link to={`/blog/`+item.slug}>
                                                    <img className="border-radius-15" src={item.image} alt={item.title} />
                                                </Link>
                                            </div>
                                            <div className="entry-content-2">
                                                <h4 className="post-title mb-15">
                                                    <Link to={`/blog/`+item.slug}>{item.title}</Link>
                                                </h4>
                                                <div className="entry-meta font-xs color-grey mt-10 pb-10">
                                                    <div>
                                                        <span className="post-on mr-10">{item.date}</span>
                                                        <span className="hit-count has-dot mr-10">By Admin</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                        )) : 
                                        <>
                                            <article key="1" className="col-xl-4 col-lg-6 col-md-6 text-center hover-up mb-30 animated">
                                                <div className="post-thumb">
                                                    <div className="skeleton skeleton-text" style={{height: "250px"}} >
                                                        <figure className="img-hover-scale overflow-hidden">
                                                            <a href=""></a>
                                                        </figure>
                                                    </div>
                                                </div>
                                                <div className="entry-content-2">
                                                    <div className="skeleton skeleton-text" style={{height: "50px"}} >
                                                        <figure className="img-hover-scale overflow-hidden">
                                                            <a href=""></a>
                                                        </figure>
                                                    </div>
                                                    <div className="entry-meta font-xs color-grey mt-10 pb-10">
                                                        <div className="skeleton skeleton-text" style={{height: "10px"}} >
                                                            <figure className="img-hover-scale overflow-hidden">
                                                                <a href=""></a>
                                                            </figure>
                                                        </div>
                                                    </div>
                                                </div>
                                            </article>
                                            <article key="2" className="col-xl-4 col-lg-6 col-md-6 text-center hover-up mb-30 animated">
                                                <div className="post-thumb">
                                                    <div className="skeleton skeleton-text" style={{height: "250px"}} >
                                                        <figure className="img-hover-scale overflow-hidden">
                                                            <a href=""></a>
                                                        </figure>
                                                    </div>
                                                </div>
                                                <div className="entry-content-2">
                                                    <div className="skeleton skeleton-text" style={{height: "50px"}} >
                                                        <figure className="img-hover-scale overflow-hidden">
                                                            <a href=""></a>
                                                        </figure>
                                                    </div>
                                                    <div className="entry-meta font-xs color-grey mt-10 pb-10">
                                                        <div className="skeleton skeleton-text" style={{height: "10px"}} >
                                                            <figure className="img-hover-scale overflow-hidden">
                                                                <a href=""></a>
                                                            </figure>
                                                        </div>
                                                    </div>
                                                </div>
                                            </article>
                                            <article key="3" className="col-xl-4 col-lg-6 col-md-6 text-center hover-up mb-30 animated">
                                                <div className="post-thumb">
                                                    <div className="skeleton skeleton-text" style={{height: "250px"}} >
                                                        <figure className="img-hover-scale overflow-hidden">
                                                            <a href=""></a>
                                                        </figure>
                                                    </div>
                                                </div>
                                                <div className="entry-content-2">
                                                    <div className="skeleton skeleton-text" style={{height: "50px"}} >
                                                        <figure className="img-hover-scale overflow-hidden">
                                                            <a href=""></a>
                                                        </figure>
                                                    </div>
                                                    <div className="entry-meta font-xs color-grey mt-10 pb-10">
                                                        <div className="skeleton skeleton-text" style={{height: "10px"}} >
                                                            <figure className="img-hover-scale overflow-hidden">
                                                                <a href=""></a>
                                                            </figure>
                                                        </div>
                                                    </div>
                                                </div>
                                            </article>
                                            
                                        </> }
                                        
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-3 primary-sidebar sticky-sidebar">
                                <div className="widget-area">
                                    <div className="sidebar-widget-2 widget_search mb-50">
                                        <div className="search-form">
                                            <form action="#">
                                                <input type="text" placeholder="Search…" />
                                                <button type="submit"><i className="fi-rs-search"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div className="sidebar-widget widget-category-2 mb-50">
                                        <h5 className="section-title style-1 mb-30">Category</h5>
                                        <ul>
                                            <li>
                                                <a href="shop-grid-right.html"> <img src="/assets/imgs/theme/icons/category-1.svg" alt="" />Milks & Dairies</a><span className="count">30</span>
                                            </li>
                                            <li>
                                                <a href="shop-grid-right.html"> <img src="/assets/imgs/theme/icons/category-2.svg" alt="" />Clothing</a><span className="count">35</span>
                                            </li>
                                            <li>
                                                <a href="shop-grid-right.html"> <img src="/assets/imgs/theme/icons/category-3.svg" alt="" />Pet Foods </a><span className="count">42</span>
                                            </li>
                                            <li>
                                                <a href="shop-grid-right.html"> <img src="/assets/imgs/theme/icons/category-4.svg" alt="" />Baking material</a><span className="count">68</span>
                                            </li>
                                            <li>
                                                <a href="shop-grid-right.html"> <img src="/assets/imgs/theme/icons/category-5.svg" alt="" />Fresh Fruit</a><span className="count">87</span>
                                            </li>
                                        </ul>
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
export default Blogs;