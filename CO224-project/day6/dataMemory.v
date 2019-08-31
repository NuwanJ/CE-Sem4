module main;

    reg [7:0] PC;   
    reg CLK, RESET;
	wire BUSY;

	reg read, write;
	reg [7:0] dataIn;
	reg [7:0] dataAddress;
	
	wire[7:0]  dataOut;
	
	// module data_mem(CLK,RESET, readEn, writeEn, dataAddress,  dataOut, dataIn , BUSY);
	data_mem myMemory(CLK,RESET,read,write,dataAddress,dataOut, dataIn,BUSY);
	
	initial begin
	    #0
		CLK <= 0;
		RESET <= 0;
		PC <= 0;
		
		#2
		dataAddress <= 0;
		dataIn <= 10;
		write <= 1;
		read <= 0;
		
		#20
		dataAddress <= 1;
		dataIn <= 11;
		write <= 1;
		read <= 0;
		
		#20
		dataAddress <= 0;
		write <= 0;
		read <= 1;
		
		#35
		dataAddress <= 1;
		write <= 0;
		read <= 1;
		
		#200 
		$finish;
    end

    always begin
        #1 CLK = ~CLK;
    end 
     always begin
        #2 PC  = PC + 1;
		$display("PC=%d, BUSY=", PC, BUSY);
    end 
endmodule


module data_mem(CLK,RESET, readEn, writeEn, dataAddress,  dataOut, dataIn , BUSY);

	input           	CLK, RESET, readEn, writeEn;
	input [7:0] 	dataAddress, dataIn;
	output [7:0] 	dataOut;
	output			BUSY;

	reg[7:0]     	outBuffer;
	reg busy;
	
	integer  i;

	reg [7:0] memory[255:0];

	// reset the memory values @ positive edge of the RESET signal
	always @(posedge RESET) begin
			busy<= 0;
			for (i=0;i<256; i=i+1)
				memory[i] <= 0;
	end

	always @(posedge CLK) begin
			// Reading data from the memory
			if(!writeEn && readEn && !busy) begin
				busy <= 1;
				#10;  // Clock * Cycles
				outBuffer <= memory[dataAddress];
				$display ("Read %d from  %d", dataOut, dataAddress);
				busy <= 0;
			end
			
			if (writeEn && !readEn && !busy) begin
				busy <= 1;
				#10;  // Clock * Cycles
				memory[dataAddress] <= dataIn;
				$display ("Written %d into  %d", dataIn, dataAddress);
				busy <= 0;
			end
	end 
	
	initial begin
	    // Initialize the memory
       	busy<= 0;
			for (i=0;i<256; i=i+1)
				memory[i] <= 0;
	end
	
	assign dataOut = outBuffer;
	assign BUSY = busy;
	
endmodule

