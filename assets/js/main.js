(function(){
  'use strict';

  document.addEventListener('DOMContentLoaded', function(){
    // Remove no-js class
    document.body.classList.remove('no-js');

    // Initialize all interactive features
    initMobileMenu();
    initScrollAnimations();
    initBackToTop();
    initInteractiveElements();
    initSmoothScroll();
  });

  /**
   * Mobile Menu Toggle
   */
  function initMobileMenu(){
    const header = document.querySelector('.site-header');
    
    // Create menu button if it doesn't exist
    let menuBtn = document.querySelector('.menu-toggle');
    if(!menuBtn){
      menuBtn = document.createElement('button');
      menuBtn.className = 'menu-toggle';
      menuBtn.innerHTML = '<span></span><span></span><span></span>';
      menuBtn.setAttribute('aria-label', 'Toggle menu');
      header.querySelector('.wrap').appendChild(menuBtn);
    }

    const nav = document.querySelector('.site-navigation');
    
    menuBtn.addEventListener('click', function(){
      this.classList.toggle('active');
      nav.classList.toggle('active');
    });

    // Close menu when a link is clicked
    const navLinks = nav.querySelectorAll('a');
    navLinks.forEach(link => {
      link.addEventListener('click', function(){
        menuBtn.classList.remove('active');
        nav.classList.remove('active');
      });
    });

    // Close menu on window resize
    window.addEventListener('resize', function(){
      if(window.innerWidth > 768){
        menuBtn.classList.remove('active');
        nav.classList.remove('active');
      }
    });
  }

  /**
   * Scroll Animations - Fade in elements as they come into view
   */
  function initScrollAnimations(){
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries){
      entries.forEach(entry => {
        if(entry.isIntersecting){
          entry.target.classList.add('in-view');
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);

    // Observe articles and project cards
    const elements = document.querySelectorAll('article, .project, .widget, .post');
    elements.forEach(el => {
      el.classList.add('fade-in');
      observer.observe(el);
    });
  }

  /**
   * Back to Top Button
   */
  function initBackToTop(){
    let backToTopBtn = document.querySelector('.back-to-top');
    
    if(!backToTopBtn){
      backToTopBtn = document.createElement('button');
      backToTopBtn.className = 'back-to-top';
      backToTopBtn.innerHTML = '↑';
      backToTopBtn.setAttribute('aria-label', 'Back to top');
      document.body.appendChild(backToTopBtn);
    }

    window.addEventListener('scroll', function(){
      if(window.scrollY > 300){
        backToTopBtn.classList.add('visible');
      } else {
        backToTopBtn.classList.remove('visible');
      }
    });

    backToTopBtn.addEventListener('click', function(){
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  /**
   * Interactive Elements - Add hover effects and transitions
   */
  function initInteractiveElements(){
    // Add ripple effect to buttons
    const buttons = document.querySelectorAll('button, .button, input[type="submit"], .more-link, .project-link, .post-edit-link');
    
    buttons.forEach(button => {
      button.addEventListener('mousedown', function(e){
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.className = 'ripple';
        
        // Remove existing ripple
        const existingRipple = this.querySelector('.ripple');
        if(existingRipple){
          existingRipple.remove();
        }
        
        this.appendChild(ripple);
      });
    });

    // Add interactive class to links on hover
    const links = document.querySelectorAll('a');
    links.forEach(link => {
      link.addEventListener('mouseenter', function(){
        this.classList.add('interactive-hover');
      });
      link.addEventListener('mouseleave', function(){
        this.classList.remove('interactive-hover');
      });
    });

    // Parallax effect on header background
    const header = document.querySelector('.site-header');
    if(header){
      window.addEventListener('scroll', function(){
        const scrollY = window.scrollY;
        header.style.backgroundPosition = `0 ${scrollY * 0.5}px`;
      });
    }
  }

  /**
   * Smooth Scroll for internal links
   */
  function initSmoothScroll(){
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if(href === '#') return;
        
        const target = document.querySelector(href);
        if(target){
          e.preventDefault();
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  }

  /**
   * Utility: Throttle function for performance
   */
  function throttle(func, delay){
    let lastCall = 0;
    return function(){
      const now = new Date().getTime();
      if(now - lastCall >= delay){
        func.apply(this, arguments);
        lastCall = now;
      }
    };
  }

})();
