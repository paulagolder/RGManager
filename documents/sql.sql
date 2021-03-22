SELECT *  FROM `seattopd` WHERE `seat` = 'LCP' and district = 'UKP' and year  ="2021"

select DISTINCT sp.pdid from seattopd as sp WHERE sp.seat="LPC" and sp.district ="UKP" and sp.year ="2021"

select sp.pdid from seattopd as sp WHERE sp.seat='LPC' and sp.district ='UKP' and sp.year ='2021'

select * from seattopd as sp WHERE sp.district="UKP" and sp.seat="LCP" and sp.year="2021"
