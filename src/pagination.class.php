<?php

declare(strict_types=1);

class pagination{
/*
Script Name: *Digg Style Paginator Class
Script URI: http://www.mis-algoritmos.com/2007/05/27/digg-style-pagination-class/
Description: Class in PHP that allows to use a pagination like a digg or sabrosus style.
Script Version: 0.4
Author: Victor De la Rocha
Author URI: http://www.mis-algoritmos.com
*/
		/*Default values*/
		public $total_pages = -1;
  //items
		public $limit;
  
		public $target = "";
   
		public $page = 1;
  
		public $adjacents = 2;
  
		public $showCounter = false;
  
		public $className = "pagination";
  
		public $parameterName = "page";
  
		public $urlF = false;//urlFriendly

		/*Buttons next and previous*/
		public $nextT = "Próxima";
  
		public $nextI = "&#187;";
   //&#9658;
		public $prevT = "Anterior";
  
		public $prevI = "&#171;"; //&#9668;

		/*****/
		public $calculate = false;
		
		#Total items
		public function items($value){$this->total_pages = (int) $value;}
		
		#how many items to show per page
		public function limit($value){$this->limit = (int) $value;}
		
		#Page to sent the page value
		public function target($value){$this->target = $value;}
		
		#Current page
		public function currentPage($value){$this->page = (int) $value;}
		
		#How many adjacent pages should be shown on each side of the current page?
		public function adjacents($value){$this->adjacents = (int) $value;}
		
		#show counter?
		public function showCounter($value=""){$this->showCounter=$value===true;}

		#to change the class name of the pagination div
		public function changeClass($value=""){$this->className=$value;}

		public function nextLabel($value){$this->nextT = $value;}
  
		public function nextIcon($value){$this->nextI = $value;}
  
		public function prevLabel($value){$this->prevT = $value;}
  
		public function prevIcon($value){$this->prevI = $value;}

		#to change the class name of the pagination div
		public function parameterName($value=""){$this->parameterName=$value;}

		#to change urlFriendly
		public function urlFriendly($value="%"){
				if(eregi('^ *$',$value)){
						$this->urlF=false;
						return false;
					}
    
				$this->urlF=$value;
    return null;
			}
		
		public $pagination;

		public function pagination(){}
  
		public function show(){
				if (!$this->calculate && $this->calculate()) {
        echo "<div class=\"$this->className\">$this->pagination</div>\n";
    }
			}
  
		public function getOutput()
  {
      if ($this->calculate) {
          return null;
      }
      if ($this->calculate()) {
          return "<div class=\"$this->className\">$this->pagination</div>\n";
      }
      return null;
  }
  
		public function get_pagenum_link($id){
				if (strpos($this->target,'?')===false) {
        if ($this->urlF) {
            return str_replace($this->urlF,$id,$this->target);
        } else {
            return sprintf('%s?%s=%s', $this->target, $this->parameterName, $id);
        }
    } else {
        return sprintf('%s&%s=%s', $this->target, $this->parameterName, $id);
    }
			}
		
		public function calculate(){
				$this->pagination = "";
				$error = false;
				if($this->urlF && $this->urlF != '%' && strpos($this->target,$this->urlF)===false){
						//Es necesario especificar el comodin para sustituir
						echo "Especificaste un wildcard para sustituir, pero no existe en el target<br />";
						$error = true;
					}elseif($this->urlF && $this->urlF == '%' && strpos($this->target,$this->urlF)===false){
						echo "Es necesario especificar en el target el comodin % para sustituir el número de página<br />";
						$error = true;
					}

				if($this->total_pages < 0){
						echo "It is necessary to specify the <strong>number of pages</strong> (\$class->items(1000))<br />";
						$error = true;
					}
    
				if($this->limit == null){
						echo "It is necessary to specify the <strong>limit of items</strong> to show per page (\$class->limit(10))<br />";
						$error = true;
					}

    if ($error) {
        return false;
    }
				
				$n = trim($this->nextT.' '.$this->nextI);
				$p = trim($this->prevI.' '.$this->prevT);                                //if no page var is given, set start to 0
			
				/* Setup page vars for display. */
				$prev = $this->page - 1;                            //previous page is page - 1
				$next = $this->page + 1;                            //next page is page + 1
				$lastpage = ceil($this->total_pages/$this->limit);        //lastpage is = total pages / items per page, rounded up.
				$lpm1 = $lastpage - 1;                        //last page minus 1
				
				/* 
					Now we apply our rules and draw the pagination object. 
					We're actually saving the code to a variable in case we want to draw it more than once.
				*/
				
				if($lastpage > 1){
						if($this->page){
								//anterior button
								if ($this->page > 1) {
            $this->pagination .= '<a href="'.$this->get_pagenum_link($prev).sprintf('" class="prev">%s</a>', $p);
        } else {
            $this->pagination .= sprintf('<span class="disabled">%s</span>', $p);
        }
							}
      
						//pages	
						if ($lastpage < 7 + ($this->adjacents * 2)){//not enough pages to bother breaking it up
								for ($counter = 1; $counter <= $lastpage; ++$counter){
										if ($counter == $this->page) {
              $this->pagination .= sprintf('<span class="current">%d</span>', $counter);
          } else {
              $this->pagination .= '<a href="'.$this->get_pagenum_link($counter).sprintf('">%d</a>', $counter);
          }
									}
							}
						elseif($lastpage > 5 + ($this->adjacents * 2)){//enough pages to hide some
								//close to beginning; only hide later pages
								if($this->page < 1 + ($this->adjacents * 2)){
										for ($counter = 1; $counter < 4 + ($this->adjacents * 2); ++$counter){
												if ($counter == $this->page) {
                $this->pagination .= sprintf('<span class="current">%d</span>', $counter);
            } else {
                $this->pagination .= '<a href="'.$this->get_pagenum_link($counter).sprintf('">%d</a>', $counter);
            }
											}
          
										$this->pagination .= "...";
										$this->pagination .= '<a href="'.$this->get_pagenum_link($lpm1).sprintf('">%s</a>', $lpm1);
										$this->pagination .= '<a href="'.$this->get_pagenum_link($lastpage).sprintf('">%s</a>', $lastpage);
									}
								//in middle; hide some front and some back
								elseif($lastpage - ($this->adjacents * 2) > $this->page && $this->page > ($this->adjacents * 2)){
										$this->pagination .= '<a href="'.$this->get_pagenum_link(1).'">1</a>';
										$this->pagination .= '<a href="'.$this->get_pagenum_link(2).'">2</a>';
										$this->pagination .= "...";
										for ($counter = $this->page - $this->adjacents; $counter <= $this->page + $this->adjacents; ++$counter)
											if ($counter == $this->page) {
               $this->pagination .= sprintf('<span class="current">%s</span>', $counter);
           } else {
               $this->pagination .= '<a href="'.$this->get_pagenum_link($counter).sprintf('">%s</a>', $counter);
           }
          
										$this->pagination .= "...";
										$this->pagination .= '<a href="'.$this->get_pagenum_link($lpm1).sprintf('">%s</a>', $lpm1);
										$this->pagination .= '<a href="'.$this->get_pagenum_link($lastpage).sprintf('">%s</a>', $lastpage);
									}
								//close to end; only hide early pages
								else{
										$this->pagination .= '<a href="'.$this->get_pagenum_link(1).'">1</a>';
										$this->pagination .= '<a href="'.$this->get_pagenum_link(2).'">2</a>';
										$this->pagination .= "...";
										for ($counter = $lastpage - (2 + ($this->adjacents * 2)); $counter <= $lastpage; ++$counter)
											if ($counter == $this->page) {
               $this->pagination .= sprintf('<span class="current">%s</span>', $counter);
           } else {
               $this->pagination .= '<a href="'.$this->get_pagenum_link($counter).sprintf('">%s</a>', $counter);
           }
									}
							}
      
						if ($this->page) {
          //siguiente button
          if ($this->page < $counter - 1) {
              $this->pagination .= '<a href="'.$this->get_pagenum_link($next).sprintf('" class="next">%s</a>', $n);
          } else {
              $this->pagination .= sprintf('<span class="disabled">%s</span>', $n);
          }

          if ($this->showCounter) {
              $this->pagination .= sprintf('<div class="pagination_data">(%s Pages)</div>', $this->total_pages);
          }
      }
					}

				return true;
			}
	}
