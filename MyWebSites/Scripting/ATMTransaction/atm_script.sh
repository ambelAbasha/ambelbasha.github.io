#!/bin/bash

if [ ! -f ATM.txt ]; then
   echo "ATM is Offline"
else  
   read -p "Give your Bank Account Number: " accno
   if [ $(grep -w "$accno" ATM.txt | wc -l ) -eq 0 ]; then
      echo "The Card Number is False"
   else
      i=0
      for str in $(grep -w "$accno" ATM.txt); do
        case $i in
         1) fname=$str ;;
         2) sname=$str ;;
         3) valid=$str ;;
         4) cash=$str ;;
        esac
        let i=$i+1
      done

      if [ "$accno" = "A" ]; then
        echo "The Card is Invalid"
      else
        read -p "Y= Ypoloipo, A= Analipsi, K= Katathesi: " ans
        case $ans in
        [Yy] ) echo
                echo "$fname $sname To Ypoloipo sas einai $cash eurw" ;;
	 
        [Kk] ) read -p "Poso Katathesis: " downcash
                let cash=$cash+$downcash
                sed 's/.*'$accno'.*/'$accno' '$sname' '$fname' '$valid' '$cash'/g' ATM.txt >> ATM2.txt
                mv ATM2.txt ATM.txt 
                
                echo
                echo "$fname $sname Katathesate $downcash eurw" ;;

        [Aa] ) read -p "Poso Analipsis: " upcash
	        let pol=$upcash%20

	        if [ $upcash -gt $cash ]; then
                   echo "Ypoloipo mi Eparkes"
                elif [ $upcash -gt 1000 ]; then
                   echo "Polu Megalo Poso"
                elif [ $pol -ne 0 ]; then
                   echo "To Poso prepei na einai Pol/sio tou 20"
                else
                  let cash=$cash-$upcash

	          let cash50=$upcash/100
	          let cash50=$cash50*2

	          let cash20=$upcash%100
	          let cash20=$cash20/20

	          sed 's/.*'$accno'.*/'$accno' '$sname' '$fname' '$valid' '$cash'/g' ATM.txt >> ATM2.txt
      	          mv ATM2.txt ATM.txt

                  echo
		  echo "$fname $sname Analipsate $upcash eurw:"
	          echo "$cash50 twn 50eurw kai $cash20 twn 20euro"
 	       fi ;;
         esac
      fi
   fi
fi
