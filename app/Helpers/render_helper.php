<?php

  if(!function_exists('render')){
    function render(string $name, array $data=[], array $options=[]){
      
        return view(
          'layout',
          ['content'=>view($name, $data, $options)],
          $options
        );
        //view 함수 내에서 extract($data) 실행 키값을 변수로 변환해서 사용
      
    }
    
  }

?>