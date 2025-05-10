<?php
// footer.php
?>
<style>
    html {
        height: 100%;
    }
    
    body {
        min-height: 100%;
        display: flex;
        flex-direction: column;
        margin: 0;
    }
    
    .content-wrapper {
        flex: 1 0 auto;
    }
    
    .site-footer {
        flex-shrink: 0;
        background: linear-gradient(to right, #1e3c72, #2a5298);
        color: white;
        width: 100%;
    }
    
    .footer-main {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 50px;
    }
    
    .footer-logo {
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .footer-nav {
        display: flex;
        gap: 25px;
    }
    
    .footer-nav a {
        color: white;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .footer-nav a:hover {
        color: #e0e0e0;
    }
    
    .footer-copyright {
        text-align: center;
        padding: 15px;
        background-color: rgba(0, 0, 0, 0.1);
        font-size: 0.9rem;
    }
    
    @media (max-width: 768px) {
        .footer-main {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }
        
        .footer-logo {
            margin-bottom: 15px;
        }
        
        .footer-nav {
            gap: 15px;
        }
    }
</style>

<footer class="site-footer">
    <div class="footer-main">
        <div class="footer-logo">Simple Blog Platform</div>
        <div class="footer-nav">
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Blog</a>
            <a href="#">Contact</a>
        </div>
    </div>
    <div class="footer-copyright">
        © <?= date('Y') ?> Simple Blog Platform. All rights reserved.
    </div>
</footer>

<!-- Script to wrap content in content-wrapper div -->
<script>
    // Only run this if content is not already wrapped
    if (!document.querySelector('.content-wrapper')) {
        window.addEventListener('DOMContentLoaded', function() {
            // Get all content between header and footer
            const body = document.body;
            const footer = document.querySelector('.site-footer');
            
            // Create wrapper for all content before footer
            const wrapper = document.createElement('div');
            wrapper.className = 'content-wrapper';
            
            // Move all content except footer into wrapper
            while (body.firstChild && body.firstChild !== footer) {
                wrapper.appendChild(body.firstChild);
            }
            
            // Insert wrapper at beginning of body
            body.insertBefore(wrapper, footer);
        });
    }
</script>