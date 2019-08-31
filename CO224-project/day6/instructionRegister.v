
module main;
   
   	wire  RESET,readEn,writeEn,busy;
	wire[7:0]  address,dataIn,dataOut;
   
    cache myCache (CLK,RESET,readEn,writeEn, address,dataIn,dataOut,busy);

    initial begin
	
	
        #0
        readEn <= 0;
		writeEn <=1;
  
        #10
		address <= 8b'000000000;// index-0, tag-0, block-0
		dataIn <=10;
		
		#20
		address <= 8b'00000001;// index-0, tag-0, block-1
        dataIn <=20;
		
		#30
		address <= 8b'00000010; // index-1, tag-0, block-0
        dataIn <=30;

		#40
		address <= 8b',00010000; // index-0, tag-1, block-0
        dataIn <=40;		
		
    end
    
    always begin
		#5 CLK=~CLK;     // Clock is 2
	end
	
endmodule

