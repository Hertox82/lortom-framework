<?php
/**
 * User: hernan
 * Date: 11/07/2018
 * Time: 11:35
 */

namespace LTFramework\Commands;


use Illuminate\Console\Command;
use File;

class DeleteGitignore extends Command {

    protected $description = "this command delete .gitignore files";

    protected $signature = "lt-gitignore:delete";

    protected $listToDelete = [
        'angular-backend/src/plugins/.gitignore',
        'resources/.gitignore',
        'template/.gitignore'
    ];


    public function handle() {

        $this->comment('deleting .gitignore files');
        foreach ($this->listToDelete as $path) {

            $file = base_path().'/'.$path;

            if(File::exists($file)) {
                File::delete($file);
            }
        }

        $this->info('just do it!');
    }

}