<?php

/**
 * Renders header for each page
 * @access public
 * @package void
 * @subpackage void
 * @category void
 * @author vivek
 * @link void
 */

Class Header {
    private $navLinks = [];

    /**
     * Sets the navigation links
     *
     * @access public
     * @param String
     * @param String
     * @param String
     * @param String
     * @return void
     */

    public function setNavLinks($link1, $name1, $link2, $name2) {
        $this->navLinks[0]['link'] = $link1;
        $this->navLinks[0]['name'] = $name1;
        $this->navLinks[1]['link'] = $link2;
        $this->navLinks[1]['name'] = $name2;
    }

    /**
     * Renders the header
     *
     * @access public
     * @param void
     * @return void
     */
    public function renderHeader() { ?>
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                            aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php">HOME</a></li>

                        <?php foreach ($this->navLinks as $navLink) { ?>

                            <li><a href="<?php echo $navLink['link'];?> "><?php echo $navLink['name'];?></a></li>

                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
<?php
    }
}
?>