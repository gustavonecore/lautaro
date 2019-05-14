Blog API
=============================

Index
--------------

#### Define database

**Add a new migration**

We'll use phinx for this, just run: `php vendor/bin/phinx create AddBlogTable`
After running the command, a new migration will be placed in `etc\migrations\`.
Then, you need to add the `up` and `down`methods.


    <?php
    
    use Phinx\Migration\AbstractMigration;
    
    class AddBlogTable extends AbstractMigration
    {
        public function up()
        {
                $post = $this->table('post');
                $post->addColumn('title', 'string', ['limit' => 250])
                    ->addColumn('content', 'string')
                    ->addColumn('created_dt', 'datetime')
                    ->addColumn('updated_dt', 'datetime', ['null' => true])
                    ->create();
        }
    
        public function down()
        {
            $this->table('post')->drop()->save();
        }
    }
    


To run your migration, just execute: `php vendor/bin/phinx migrate`.
For more details about how to use Phinx, please refer to the docs: [Phinx](http://docs.phinx.org/en/latest/migrations.html "Phinx")

**Create Post model**

Leftaro used Propel as ORM. To create the related model just run:
`php bin/update-model.php`. This will create a new model inside the `Leftaro\App\Model` namespace.

Leftaro have some useful traits to make models a bit more usable, just take a look to the final Post model.



    <?php
    
    namespace Leftaro\App\Model;
    
    use Leftaro\App\Model\Base\Post as BasePost;
    use Leftaro\App\ModelMapperTrait;  // This was added
    
    /**
     * Skeleton subclass for representing a row from the 'post' table.
     *
     *
     *
     * You should add additional methods to this class to meet the
     * application requirements.  This class will only be generated as
     * long as it does not already exist in the output directory.
     *
     */
    class Post extends BasePost
    {
    	use ModelMapperTrait; // This was added
    }


#### Create post

**Endpoint**

Since we have our table ready to go, we need to create blog items. For that, we'll define the url as: 

    POST /api/v1/post
    {
        title: string,
        content: string
    }

**APi versions**
Don't worry about the `/api/v1` Leftaro it's shipped with api versioning, so this will be included by default in all your endpoints.

**Create the controller**

In order to make the endpoint available from the server, we need to create a new controller, called `PostController` inside the `Controller\Api` namespace. 
For a better understand about the routing rules, check out the related doc here about [Smart-routing](https://github.com/gustavonecore/oss-leftaro/smart-routing.md "Smart-routing").

Add the POST handler method to the controller Blog as follow:



    <?php namespace Leftaro\App\Controller\Api;
    
    use Leftaro\App\Controller\Api\ApiController;
    use Zend\Diactoros\Response;
    use Zend\Diactoros\ServerRequest;
    use Leftaro\App\Model\Post;
	
    /**
     * Post controller
     */
    class PostController extends ApiController
    {
    	/**
    	 * Create a new ot
    	 *
    	 * @param ServerRequest $request
    	 * @param Response $response
    	 * @return Response
    	 */
    	public function postCollectionAction(ServerRequest $request, Response $response) : Response
    	{
    		$input = $this->verify([
    			'title' => 'string',
    			'content' => 'string',
    		], $request->getParsedBody(), ['title', 'content']);
    
    		$post = Post::create([
    			'title' => $input['title'],
    			'content' => $input['content'],
    		]);
    
    		return $this->json([
    			'data' => [
    				'post' => $post->asArray(),
    			],
    		]);
    	}
    }

**Sanitization**
You can take a look to the `verify` method inside the `Leftaro\App\Controller\BaseController` to check how it works and how is sanitizing your inputs. Leftaro uses under the hood the [Gcore Sanitizer](https://github.com/gustavonecore/sanitizer "Gcore Sanitizer").

Now you are ready to try it with postman. Try to create a post without the required fields (title and content).



3. Read blog list
4. Update blog
5. Delete a blog entry
6. Decouple with command and handlers
7. Error handling
9. Middlewares



