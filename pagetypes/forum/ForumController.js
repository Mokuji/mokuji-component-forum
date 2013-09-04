;(function($, exports){
  
  var ForumController = PageType.sub({
    
    //Define the tabs to be created.
    tabs: {'Content': 'contentTab'},
    
    //Define the elements for jsFramework to keep track of.
    elements: {
      'form': '#forum-contentTab-form',
      'title': '#forum-contentTab-form .title',
      'sub_fora': 'ol.sub-fora',
      'form_add_sub_forum': '#forum-createForum-form',
      'btn_add_sub_forum': '.sub-fora-operations .add-sub-forum'
    },
    
    events: {
      
      'click on btn_add_sub_forum': function(e){
        e.preventDefault();
        this.form_add_sub_forum.slideToggle();
      },
      
      //Let findability know we have a recommended default.
      'keyup on title': function(e){
        app.Page.Tabs.findabilityTab.recommendTitle($(e.target).val(), 'ALL');
      }
      
    },
    
    //Retrieve input data stub.
    getData: function(pageId)
    {
      
      this.pageId = pageId;
      
      return $.rest('GET', '?rest=forum/page_forum/'+pageId, {
        allow_create:true,
        include_extras:true,
        include_link:true,
        include_subfora:true
      });
      
    },
    
    //After render stub.
    afterRender: function()
    {
      
      //In case the server created a new title for us.
      app.Page.Tabs.findabilityTab.recommendTitle(this.title.val(), 'ALL');
      
      //Main forum submitting.
      this.form.restForm({
        beforeSubmit: this.proxy(this.beforeSave)
      });
      
      //Sub forum sorting.
      this.makeSortable();
      
      //Sub forum adding.
      this.form_add_sub_forum.restForm({
        beforeSubmit: this.proxy(this.beforeSubForumCreate),
        success:this.proxy(this.afterSubForumCreate)
      });
      
      //Sub forum editing.
      this.makeEditSubforumForm(this.view.find('.edit-sub-forum-form'));
      this.view.on('click', '.forum .operations .edit-forum', function(e){
        e.preventDefault();
        $(e.target).closest('.forum').find('> .edit-sub-forum-form').slideToggle();
      });
      
      //Sub forum deleting.
      this.view.on('click', '.forum .operations .delete-forum', function(e){
        
        e.preventDefault();
        
        //Get confirmation.
        if(confirm(transf('forum', 'Are you sure you want to delete this sub forum? This can not be undone!')) !== true)
          return;
        
        $forum = $(e.target).closest('.forum');
        $.rest('DELETE', '?rest=forum/forum/'+$forum.attr('data-forum-id'))
          .done(function(){
            $forum.slideToggle(function(){
              $forum.remove();
            });
          })
          .error(function(xhr, state, message){
            alert(message);
          });
        
      });
      
    },
    
    //Save stub.
    save: function(pageId){
      this.form.trigger('submit');
    },
    
    //Before restForm submits it's data.
    beforeSave: function(data)
    {
      
      data.subfora = this.sub_fora.nestedSortable('toArray', {
        attribute: 'data-forum-id',
        expression: (/()([0-9]+)/),
        omitRoot: true
      });
      
      return data;
      
    },
    
    //After save stub.
    afterSave: function(data){},
    
    beforeSubForumCreate: function(data)
    {
      
      data.parent_forum_id = this.form.find('input[name=id]').val();
      
      return data;
      
    },
    
    //When a sub forum is created.
    afterSubForumCreate: function(data)
    {
      
      this.form_add_sub_forum.hide();
      
      $subforum = $(this.renderTemplate('forumList', {0:data}));
      this.sub_fora.prepend($subforum);
      this.makeEditSubforumForm($subforum.find('.edit-sub-forum-form'));
      
      this.makeSortable();
      
    },
    
    makeSortable: function()
    {
      
      this.sub_fora.nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div',
        listType: 'ol',
        maxLevels: 3,
        opacity: 0.6
      });
      
    },
    
    makeEditSubforumForm: function(element){
      $(element).restForm({
        success: function(data, form){
          $form = $(form);
          $forum = $form.closest('.forum');
          $forum.find('> .info .meta .title').text(data.title);
          $forum.find('> .info .meta .description').text(data.description);
          $form.slideToggle();
        }
      });
    }
    
  });
  
  //Export the namespaced class.
  ForumController.exportTo(exports, 'cmsBackend.forum.ForumController');
  
})(jQuery, window);
