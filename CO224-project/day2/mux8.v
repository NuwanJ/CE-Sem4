module main;

    reg [7:0] A,B;
    reg [2:0] sel;
    output [7:0] R;
    
    reg CLK;
    reg muxSelect;

	mux8bit2 mux(R,A,B,sel);
	
	initial begin
	    #0
		A <= 8'b00000111;
		B <= 8'b00000011;
		
		sel <= 0; 
		// 0:FORWARD 1:ADD 2:AND 3:OR
	
		#10 $display("A=%d B=%d S=%d Output=%d \n", A, B, sel, R);
		$finish;
    end

    always #5 CLK = ~CLK;
    
endmodule

module mux8bit2(OUT, A,B, S);
   
    input [7:0] A,B;        // A and B input data paths
    input S;          // selector input
    
    output [7:0] OUT;       // Output data path
    
    reg [7:0] R;

    always @(S) begin       // if(S==0){R=A} else{R=B}
        R <= (S == 0) ? A : B;
    end

    assign OUT = R;

endmodule
