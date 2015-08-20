<h3>GENERATE DATA DICTIONARY<h3>

        <h3><a  href='?action=newDD' onclick="return confirm('Are you sure ,you want to Generate NEW DD')"> CLICK HERE TO GENERATE New DD DEFINITION</a></h3>

        <h3><a  href='?action=appendDD' onclick="return confirm('Are you sure ,you want to UPDATE DD')"> CLICK HERE TO UPDATE/Append  DD DEFINITION</a></h3>


        <?php

        /**
         * Created by Susmit.
         * User: work
         * Date: 10/12/14
         * Time: 3:25 AM
         */
        function newDD() {

            require('dictionaryConfig.php');

            /*
             * 
             * emptying the DD table
             */

            // $con = connect();


            $con->query("truncate table $DDtbl");


            $rs = $con->query("select * from $DDT");


            while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {

                //print_r($row);die;
                unset($row['dict_id']);

                $ddKeys = array('database_table_name', 'table_alias', 'table_type', 'tab_name', 'parent_key', 'parent_table', 'list_filter', 'list_sort');


                foreach ($ddKeys as $k) {

                    if (isset($row[$k])) {

                        $dd_key = (array_keys($DEFAULT['DD']));

                        foreach ($dd_key as $key) {

                            $row[$k] = str_replace($key, $DEFAULT['DD'][$key], $row[$k]);
                        }
                    }///inner foreach
                }///outer foreach


                insert($DDtbl, $row);
            }

            echo "<pre> Data Dictionary Generated Successfully";
            $con->close();
        }

///end of newDD generation

        function appendDD() {

            require('dictionaryConfig.php');


            $rs = $con->query("select * from $DDtbl");

            while ($row = $rs->fetch_array(MYSQLI_ASSOC)) {



                $id = $row['dict_id'];

                unset($row['dict_id']);

                $ddKeys = array('database_table_name', 'table_alias', 'table_type', 'tab_name', 'parent_key', 'parent_table', 'list_filter', 'list_sort');


                foreach ($ddKeys as $k) {

                    if (isset($row[$k])) {

                        $dd_key = (array_keys($DEFAULT['DD']));

                        foreach ($dd_key as $key) {

                            $row[$k] = str_replace($key, $DEFAULT['DD'][$key], $row[$k]);
                        }
                    }///inner foreach
                }///outer foreach


                update($DDtbl, $row, array('dict_id' => $id));
            }

            echo "<pre> Data Dictionary Updated Successfully";
            $con->close();
        }

        if (isset($_GET['action'])) {

            $_GET['action']();
        } 