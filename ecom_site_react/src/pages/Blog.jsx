import React,{useContext,useState,useEffect} from "react";
import fetch from 'node-fetch';
import { useSsrState, useSsrEffect } from '@issr/core';
import {Link,useParams } from "react-router-dom";
import MetaTags from 'react-meta-tags';
import {BaseUrlContext} from '../App';

const Blog = ()=>{

    const base_url = useContext(BaseUrlContext);
    const [getSingle, setSingle] = useSsrState([]);
    const [latestBlog, setLatestBlog] = useState([]);
    const params = useParams();
    useEffect(() => {
        window.scrollTo({top: 0, behavior: 'smooth'});
    });

     
    useEffect(() => {
        
        fetch(`${base_url}/blog-list`).then(res => res.json())
        .then((result) => {
                
            setLatestBlog(result.result);
            }
        )
        
    },[params.slug]);

    useEffect(() => {
        
        fetch(`${base_url}/blog-detail`,{
            method: "POST",
            headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
            },
            body: JSON.stringify({
                slug: params.slug,
              })
        }).then(res => res.json())
        .then((result) => {
                
            setSingle(result.result);
            }
        )
        
    },[params.slug]);


    const getSingleBlog = () => {
        
        return fetch(`${base_url}/blog-detail`,
                    {
                        method: "POST",
                        headers: {
                                Accept: "application/json",
                                "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            slug: params.slug,
                          })
                    })
        .then(data => data.json())
        
    }; 
    
    useSsrEffect(async () => {
        
        const data = await getSingleBlog();
        setSingle(data.result);
        console.log(data);

    });

    
    
    function sharePost(type,url){ 
        
        if(type=='facebook'){
            window.open( "https://www.facebook.com/sharer/sharer.php?u="+url,  "_blank", "width=600, height=450"); 

        }else if(type=='twitter'){
            window.open( 
                "https://twitter.com/intent/tweet?url="+url, 
                "_blank", "width=600, height=450"); 
        }else if(type=='linkedin'){
            window.open( 
                "https://www.linkedin.com/shareArticle?mini=true&url="+url, 
                "_blank", "width=600, height=450"); 
        }else if(type=='pinterest'){
            window.open( 
                "https://pinterest.com/pin/create/button/?url="+url, 
                "_blank", "width=600, height=450"); 
        }
        else if(type=='instagram'){
            window.open( 
                "https://www.instagram.com/?url="+url, 
                "_blank", "width=600, height=450"); 
        }
        else if(type=='google'){
            window.open( 
                'https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=&su=Propira&body=http://www.rajasthansainikschool.com/&ui=2&tf=1&pli=1?', 
                "_blank", "width=600, height=450"); 
        }else if(type=='whatsup'){
          var number = '+917742544602';
          var message = url.split(' ').join('%20');
          window.open( 
              "https://api.whatsapp.com/send?text=%20" + ''+message, 
              "_blank", "width=600, height=450"); 
        }
    }


    return(
        <>
            <MetaTags>
                <title>{getSingle.title}</title>
                <meta name="description" content={getSingle.title} />
            </MetaTags>
            <main className="main">
                
                <div className="page-content mb-50">
                    <div className="container">
                        <div className="row">
                            <div className="col-xl-11 col-lg-12 m-auto">
                                <div className="row">
                                    <div className="col-lg-9">
                                        <div className="single-page pt-50 pr-30">
                                            <div className="single-header style-2">
                                                <div className="row">
                                                    <div className="col-xl-10 col-lg-12 m-auto">
                                                        <h2 className="mb-10">{getSingle.title}</h2>
                                                        <div className="single-header-meta">
                                                            <div className="entry-meta meta-1 font-xs mt-15 mb-15">
                                                                <a className="author-avatar" href="#">
                                                                    <img className="img-circle" src={getSingle.image} alt={getSingle.title} />
                                                                </a>
                                                                <span className="post-by">By <a href="#">Admin</a></span>
                                                                <span className="post-on has-dot">{getSingle.date}</span>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <figure className="single-thumbnail">
                                                <img src={getSingle.image} alt={getSingle.title} />
                                            </figure>
                                            <div className="single-content">
                                                <div className="row">
                                                    <div className="col-xl-10 col-lg-12 m-auto">

                                                        <div dangerouslySetInnerHTML={{__html: getSingle.description}}></div>

                                                        <div className="entry-bottom mt-50 mb-30">
                                                            <div className="tags w-50 w-sm-100">
                                                                <a href="blog-category-big.html" rel="tag" className="hover-up btn btn-sm btn-rounded mr-10">deer</a>
                                                                <a href="blog-category-big.html" rel="tag" className="hover-up btn btn-sm btn-rounded mr-10">nature</a>
                                                                <a href="blog-category-big.html" rel="tag" className="hover-up btn btn-sm btn-rounded mr-10">conserve</a>
                                                            </div>
                                                            <div className="social-icons single-share">
                                                                <ul className="text-grey-5 d-inline-block">
                                                                    <li><strong className="mr-10">Share this:</strong></li>
                                                                    <li className="social-facebook">
                                                                        <a href="#" onClick={() => sharePost('facebook',`http://localhost:4000/blog/${params.slug}`)}><img src="/assets/imgs/theme/icons/icon-facebook.svg" alt="" /></a>
                                                                    </li>
                                                                    <li className="social-twitter">
                                                                        <a href="#"><img src="/assets/imgs/theme/icons/icon-twitter.svg" alt="" /></a>
                                                                    </li>
                                                                    <li className="social-instagram">
                                                                        <a href="#"><img src="/assets/imgs/theme/icons/icon-instagram.svg" alt="" /></a>
                                                                    </li>
                                                                    <li className="social-linkedin">
                                                                        <a href="#"><img src="/assets/imgs/theme/icons/icon-pinterest.svg" alt="" /></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="col-lg-3 primary-sidebar sticky-sidebar pt-50">
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

                                            <div className="sidebar-widget product-sidebar mb-50 p-30 bg-grey border-radius-10">
                                                <h5 className="section-title style-1 mb-30">Latest Blogs</h5>
                                                { latestBlog.length != 0 ? latestBlog.map((item, index) => (
                                                    <div key={index} className="single-post clearfix">
                                                        <div className="image">
                                                            <img src={item.image} alt="#" />
                                                        </div>
                                                        <div className="content">
                                                            <h5><Link to={`/blog/`+item.slug} >{item.title}</Link></h5>
                                                            
                                                        </div>
                                                    </div>
                                                )) : <>
                                                
                                                    <div className="single-post clearfix">
                                                        <div className="skeleton skeleton-text" style={{height: "100px"}} >
                                                            <figure className="img-hover-scale overflow-hidden">
                                                                <a href=""></a>
                                                            </figure>
                                                        </div>
                                                        <div className="content pt-10">
                                                            <div className="skeleton skeleton-text" style={{height: "10px"}} >
                                                                <figure className="img-hover-scale overflow-hidden">
                                                                    <a href="#"></a>
                                                                </figure>
                                                            </div>
                                                            <div className="skeleton skeleton-text" style={{height: "10px"}} >
                                                                <figure className="img-hover-scale overflow-hidden">
                                                                    <a href="#"></a>
                                                                </figure>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="single-post clearfix">
                                                        <div className="skeleton skeleton-text" style={{height: "100px"}} >
                                                            <figure className="img-hover-scale overflow-hidden">
                                                                <a href=""></a>
                                                            </figure>
                                                        </div>
                                                        <div className="content pt-10">
                                                            <div className="skeleton skeleton-text" style={{height: "10px"}} >
                                                                <figure className="img-hover-scale overflow-hidden">
                                                                    <a href="#"></a>
                                                                </figure>
                                                            </div>
                                                            <div className="skeleton skeleton-text" style={{height: "10px"}} >
                                                                <figure className="img-hover-scale overflow-hidden">
                                                                    <a href="#"></a>
                                                                </figure>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </>
                                                }
                                                
                                            </div>
                                            <div className="sidebar-widget widget_instagram mb-50">
                                                <h5 className="section-title style-1 mb-30">Gallery</h5>
                                                <div className="instagram-gellay">
                                                    <ul className="insta-feed">
                                                    { latestBlog.length != 0 ? latestBlog.map((item, index) => (
                                                        <li>
                                                            <Link to={`/blog/`+item.slug} ><img className="border-radius-5" src={item.image} alt="" /></Link>
                                                        </li>
                                                        )) : <></> }
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <div className="sidebar-widget widget-tags mb-50 pb-10">
                                                <h5 className="section-title style-1 mb-30">Popular Tags</h5>
                                                <ul className="tags-list">
                                                    <li className="hover-up">
                                                        <a href="blog-category-grid.html"><i className="fi-rs-cross mr-10"></i>Cabbage</a>
                                                    </li>
                                                    <li className="hover-up">
                                                        <a href="blog-category-grid.html"><i className="fi-rs-cross mr-10"></i>Broccoli</a>
                                                    </li>
                                                    <li className="hover-up">
                                                        <a href="blog-category-grid.html"><i className="fi-rs-cross mr-10"></i>Smoothie</a>
                                                    </li>
                                                    <li className="hover-up">
                                                        <a href="blog-category-grid.html"><i className="fi-rs-cross mr-10"></i>Fruit</a>
                                                    </li>
                                                    <li className="hover-up mr-0">
                                                        <a href="blog-category-grid.html"><i className="fi-rs-cross mr-10"></i>Salad</a>
                                                    </li>
                                                    <li className="hover-up mr-0">
                                                        <a href="blog-category-grid.html"><i className="fi-rs-cross mr-10"></i>Appetizer</a>
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
            </main>
            
        </>
    )
}
export default Blog;