import path from 'path';
import React from 'react';
import Koa from 'koa';
import serve from 'koa-static';
import Router from 'koa-router';
import { StaticRouter } from 'react-router';
import serialize from 'serialize-javascript';
import App from './App';
import { serverRender } from '@issr/core';
import MetaTagsServer from 'react-meta-tags/server';
import { MetaTagsContext } from 'react-meta-tags';

const app = new Koa();
const router = new Router();

app.use(serve(path.resolve(__dirname, '../public')));

router.get('/*', async (ctx) => {
  const { url } = ctx.request;
  const routerParams = {
    location: url,
    context: {}
  };

  
  const metaTagsInstance = MetaTagsServer();
  

  const { html, state } = await serverRender(() => (
    <MetaTagsContext extract={metaTagsInstance.extract}>
      <StaticRouter {...routerParams}>
        <App />
      </StaticRouter>
    </MetaTagsContext>
  ));

  const meta = metaTagsInstance.renderToString();

  ctx.body = `
  <!DOCTYPE html>
<html lang="en">
<head>
    ${meta}
    <meta charset="UTF-8">
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:title" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <link rel="shortcut icon" type="image/x-icon" href="/assets/imgs/theme/favicon.svg" />
    <link rel="stylesheet" href="/assets/css/plugins/animate.min.css" />
    <link rel="stylesheet" href="/assets/css/main.css?v=5.2" />
		<link rel="stylesheet" href="/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/css/owl.theme.default.min.css">        
    <script src="https://kit.fontawesome.com/0b8334f960.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/3080004259.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" charset="UTF-8" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css" /> 
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css" />
    <style>
        .js-image-zoom__zoomed-image{
          z-index: 99;
        }
        .slick-prev {
          left: 20px;
          /* z-index: 99; */
        }
        .slick-next {
            right: 34px;
        }
        .slick-next:focus, .slick-next:hover, .slick-prev:focus, .slick-prev:hover {
         
          background-color: green !important;
      }
        .slick-next, .slick-prev {
         
          z-index: 9999;
          background-color: green;
          border-radius: 32px;
      }
      .skeleton {
        animation: skeleton-loading 1s linear infinite alternate;
      }
      
      @keyframes skeleton-loading {
        0% {
          background-color: hsl(200, 20%, 80%);
        }
        100% {
          background-color: hsl(200, 20%, 95%);
        }
      }
      
      .skeleton-text {
        width: 100%;
        height: 0.7rem;
        margin-bottom: 0.5rem;
        border-radius: 0.25rem;
      }
      
      .skeleton-text__body {
        width: 75%;
      }
      
      .skeleton-footer {
        width: 30%;
      }
      .spinner-border {
          width: 20px !important;
          height: 20px !important;
      }
        
    </style>
    <script>
      window.SSR_DATA = ${serialize(state, { isJSON: true })}
    </script>
</head>
<body>
    <div id="root">${html}</div>
    <script src="/index.js"></script>
    <script data-cfasync="false " src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js "></script><script src="assets/js/vendor/modernizr-3.6.0.min.js "></script>
    <script src="/assets/js/vendor/jquery-migrate-3.3.0.min.js "></script>
    <script src="/assets/js/vendor/jquery-3.6.0.min.js "></script>
    <script src="/assets/js/vendor/bootstrap.bundle.min.js "></script>
    <script src="/assets/js/plugins/slick.js "></script>
    <script src="/assets/js/plugins/jquery.syotimer.min.js "></script>
    <script src="/assets/js/plugins/waypoints.js "></script>
    <script src="/assets/js/plugins/wow.js"></script>
    <script src="/assets/js/plugins/perfect-scrollbar.js "></script>
    <script src="/assets/js/plugins/magnific-popup.js "></script>
    <script src="/assets/js/plugins/select2.min.js "></script>
    <script src="/assets/js/plugins/counterup.js "></script>
    <script src="/assets/js/plugins/jquery.countdown.min.js "></script>
    <script src="/assets/js/plugins/images-loaded.js "></script>
    <script src="/assets/js/plugins/isotope.js "></script>
    <script src="/assets/js/plugins/scrollup.js "></script>
    <script src="/assets/js/plugins/jquery.vticker-min.js "></script>
    <script src="/assets/js/plugins/jquery.theia.sticky.js "></script>
    <script src="/assets/js/plugins/jquery.elevatezoom.js "></script>
    <script src="/assets/js/main.js?v=5.2 "></script>
    <script src="/assets/js/shop.js?v=5.2 "></script>
    
    <script src="https://kit.fontawesome.com/0b8334f960.js" crossorigin="anonymous"></script>
    <script>
        $('.categories-button-active').click(function() {
           
            $('.categories-dropdown-wrap').toggleClass('open');
        });

        
    </script>
</body>
</html>
`;
});

app
  .use(router.routes())
  .use(router.allowedMethods());

const server = app.listen(4000, () => {
  console.log(`Server is listening ${4000} port`);
});
