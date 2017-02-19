<?php
/**
 * @class
* Test properties of our codebase rather than our actual code.
*/
class MetaTest extends \PHPUnit_Framework_TestCase
{
    // Possible names for php-cs-fixer, and found one.
    private $commands = array('php-cs-fixer.phar', 'php-cs-fixer','/home/claudio/devel/PHP/php-cs-fixer.phar');
    private $command = null;

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->command = 'vendor/bin/php-cs-fixer';
    }

    /**
     * Test for PSR-2.
     */
    public function testPSR2()
    {
        // If we can't find the command-line tool, we mark the test as skipped
        // so it shows as a warning to the developer rather than passing silently.
        if (!$this->command) {
            $this->markTestSkipped(
                'Needs linter to check PSR-2 compliance'
                );
        }

        // Let's check PSR-2 compliance for our code, our tests and our index.php
        // Add any other pass you want to test to this array.
        foreach (array("src/", "tests/", "web/app.php","web/app_dev.php") as $path) {
            // Run linter in dry-run mode so it changes nothing.
            exec(
                "$this->command fix --rules=@PSR2 --dry-run --diff "
                . $_SERVER['PWD'] . "/$path",
                $output,
                $return_var
                );

            // If we've got output, pop its first item ("Fixed all files...")
            // and trim whitespace from the rest so the below makes sense.
            if ($output) {
                array_pop($output);
                $output = array_map("trim", $output);
            }

            // Check shell return code: if nonzero, report the output as a failure.
            $this->assertEquals(
                0,
                $return_var,
                "PSR-2 linter reported errors in $path/: " . join("; ", $output)
                );
        }
    }
}
