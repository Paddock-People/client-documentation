<link rel="stylesheet" href="<?php echo ATLAS_DOCS_PLUGIN_URL; ?>assets/styles.css">

<div class="atlas-docs">
  <div class="container">

    <div class="row">
      <div class="col-md-4 col-lg-3 pb-20 list-border">
        <h4 class="mb-20">Help Articles</h4>
        <div id="atlas-docs"></div>
        <div id="no-results" style="display: none;">No results found.</div>
      </div>
      <div class="col-md-8 col-lg-9 mt-md-0 mt-30">
        <div id="post-content">
          <h2 class="mb-0">Client Documentation</h2>
          <p>Brought to you by the Paddock People</p>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function() {
        let searchInput = document.getElementById("search-input");
        let postList = document.getElementById("atlas-docs");
        let noResults = document.getElementById("no-results");
        let allPosts = [];

        fetch(window.location.origin + "/wp-json/wp/v2/atlas-documentation?per_page=100")
          .then(function(response) {
            return response.json();
          })
          .then(function(posts) {
            posts.sort((a, b) => a.title.rendered < b.title.rendered ? -1 : a.title.rendered === b.title.rendered ?
              0 : 1)
            allPosts = posts;
            renderPosts(allPosts);
          });

        function renderPosts(posts) {
          postList.innerHTML = '';
          noResults.style.display = "none";

          if (posts.length === 0) {
            noResults.style.display = "block";
          } else {
            posts.forEach(function(post) {
              let postLink = document.createElement("a");
              postLink.href = "#";
              postLink.className = "post-link";
              postLink.dataset.postId = post.id;
              postLink.innerText = post.title.rendered;
              postLink.addEventListener("click", function(e) {
                e.preventDefault();
                let postId = e.target.dataset.postId;
                let postContent = document.getElementById("post-content");
                fetch(window.location.origin + "/wp-json/wp/v2/atlas-documentation/" + postId)
                  .then(function(response) {
                    return response.json();
                  })
                  .then(function(post) {
                    postContent.innerHTML = "<h2 class='mb-2'>" + post.title.rendered + "</h2>" + post
                      .content.rendered;

                    // Update the URL
                    let postTitle = post.title.rendered.replace(/\s+/g, "-")
                      .toLowerCase(); // Replace spaces with hyphens
                    let newUrl = window.location.href.split("#")[0] + "#" +
                      postTitle; // Update the URL with the post title
                    history.pushState({}, "", newUrl); // Update the URL without reloading the page
                  });
              });
              postList.appendChild(postLink);
            });
          }
        }

        // Add event listener for hashchange event
        window.addEventListener("hashchange", handleHashChange);

        // Call handleHashChange initially to load the post content if the URL has a hash fragment
        handleHashChange();

        function handleHashChange() {
          var postTitle = getPostTitleFromUrl();
          if (postTitle) {
            fetchPostContent(postTitle);
          } else {
            // Handle the case when there is no post title in the URL
            // You can display a default content or show an error message
            // For example, postContent.innerHTML = 'No post selected.';
          }
        }

        function getPostTitleFromUrl() {
          var hash = window.location.hash;
          if (hash) {
            // Remove the leading "#" character and any leading/trailing whitespaces
            var postTitle = hash.substr(1).trim();
            return postTitle;
          }
          return null;
        }

        function fetchPostContent(postTitle) {
          var postContent = document.getElementById("post-content");
          fetch(window.location.origin + "/wp-json/wp/v2/atlas-documentation/?slug=" + encodeURIComponent(postTitle))
            .then(function(response) {
              return response.json();
            })
            .then(function(posts) {
              if (posts.length > 0) {
                postContent.innerHTML = "<h2 class='mb-2'>" + posts[0].title.rendered + "</h2>" + posts[0].content
                  .rendered;
              }
            });
        }


        searchInput.addEventListener("input", function() {
          let searchTerm = searchInput.value.toLowerCase();
          let filteredPosts = allPosts.filter(function(post) {
            let title = post.title.rendered.toLowerCase();
            return title.includes(searchTerm);
          });
          renderPosts(filteredPosts);
        });
      });
    </script>

  </div>
</div>